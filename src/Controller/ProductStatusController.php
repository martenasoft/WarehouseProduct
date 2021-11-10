<?php

namespace MartenaSoft\WarehouseProduct\Controller;

use App\Entity\ProductStatus;
use App\Form\ProductStatusType;
use App\Repository\ProductStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/status")
 */
class ProductStatusController extends AbstractController
{
    /**
     * @Route("/list", name="product_status_index", methods={"GET"})
     */
    public function index(ProductStatusRepository $productStatusRepository): Response
    {
        return $this->render('product_status/index.html.twig', [
            'product_statuses' => $productStatusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_status_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productStatus = new ProductStatus();
        $form = $this->createForm(ProductStatusType::class, $productStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productStatus);
            $entityManager->flush();

            return $this->redirectToRoute('product_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_status/new.html.twig', [
            'product_status' => $productStatus,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_status_show", methods={"GET"})
     */
    public function show(ProductStatus $productStatus): Response
    {
        return $this->render('product_status/show.html.twig', [
            'product_status' => $productStatus,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_status_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductStatus $productStatus): Response
    {
        $form = $this->createForm(ProductStatusType::class, $productStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_status/edit.html.twig', [
            'product_status' => $productStatus,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_status_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductStatus $productStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
