<?php

namespace MartenaSoft\WarehouseProduct\Controller;

use MartenaSoft\WarehouseCommon\Entity\Interfaces\SavedStatusInterface;
use MartenaSoft\WarehouseProduct\Entity\Product;
use MartenaSoft\WarehouseProduct\Form\ProductType;
use MartenaSoft\WarehouseProduct\Repository\ProductRepository;
use MartenaSoft\WarehouseProduct\Entity\ApplaySafe;
use MartenaSoft\WarehouseSafe\Entity\Safe;
use MartenaSoft\WarehouseProduct\Form\ApplaySafeType;
use MartenaSoft\WarehouseSafe\Service\SafeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MartenaSoft\WarehouseReports\Entity\Operation;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, SafeService $safeService): Response
    {
        $product = new Product();
        $product
            ->setRecommendedPrice(0)
            ->setDateCreate(new \DateTime());
        ;

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($product->getArticul())) {
                $rundom = bin2hex(\random_bytes(8));
                $product->setArticul($rundom);
            }

            $status = $product->getStatus();
            $product->setSavedStatus(SavedStatusInterface::STATUS_SUCCESS);
            if (in_array($status->getSafeMoneyOperation(), [Operation::TYPE_ADD, Operation::TYPE_DEDUCT])) {
                $product->setSavedStatus(SavedStatusInterface::STATUS_IN_PROCESS);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            if ($product->getSavedStatus() == Product::STATUS_IN_PROCESS) {
                return $this->redirectToRoute('safe_income_product', ['id' => $product->getId()]);
            }

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $status = $product->getStatus();
            if (in_array($status->getSafeMoneyOperation(), [Operation::TYPE_ADD, Operation::TYPE_DEDUCT])) {
                $product->setSavedStatus(SavedStatusInterface::STATUS_IN_PROCESS);
            }


            if ($product->getSavedStatus() == Product::STATUS_IN_PROCESS) {
                return $this->redirectToRoute('safe_income_product', ['id' => $product->getId()]);
            }

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/income-product/{id}", name="safe_income_product", methods={"GET", "POST"})
     */
    public function safeStageMoneyProduct(Request $request, Product $product, SafeService $safeService): Response
    {
        $applaySafe = new ApplaySafe();
        $applaySafeType = $this->createForm(ApplaySafeType::class, $applaySafe);
        $applaySafeType->handleRequest($request);

        if ($applaySafeType->isSubmitted()) {

            $logName = $logDescription = $product->getName() .' '.$product->getStatus()->getName();
            $safe = $this->getDoctrine()->getManager()->find(Safe::class, $applaySafe->getTypes());
            $product->setSavedStatus(Product::STATUS_SUCCESS);
            if ($product->getStatus()->getSafeMoneyOperation() == \MartenaSoft\WarehouseReports\Entity\Operation::TYPE_ADD) {
                $safe->setSum($safe->getSum() + $product->getBoughtPrice());
                $logName .= ' added to safe  %d (old sum: %d)';
                $logDescription .= ' added to safe %d (old sum: %d)';
                $this->getDoctrine()->getManager()->flush();
                $safeService->income($product->getBoughtPrice(), 0, $logName, $logDescription);
            }

            if ($product->getStatus()->getSafeMoneyOperation() == Operation::TYPE_DEDUCT) {
                $safe->setSum($safe->getSum() - $product->getBoughtPrice());
                $logName .= ' deducted to safe  %d (old sum: %d)';
                $logDescription .= ' deducted to safe %d (old sum: %d)';
                $this->getDoctrine()->getManager()->flush();
                $safeService->withdraw($product->getBoughtPrice(), 0, $logName, $logDescription);
            }

            return $this->redirectToRoute('product_index');
        }

        return $this->render('@Product/safe/income_product.html.twig', [
            'product' => $product,
            'form' => $applaySafeType->createView()
        ]);
    }

}
