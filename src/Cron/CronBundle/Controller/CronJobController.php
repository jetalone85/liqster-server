<?php

namespace Cron\CronBundle\Controller;

use Cron\CronBundle\Entity\CronJob;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cronjob controller.
 *
 * @Route("cronjob")
 */
class CronJobController extends Controller
{
    /**
     * Lists all cronJob entities.
     *
     * @Route("/", name="cronjob_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cronJobs = $em->getRepository('CronBundle:CronJob')->findAll();

        return $this->render('cronjob/index.html.twig', array(
            'cronJobs' => $cronJobs,
        ));
    }

    /**
     * Creates a new cronJob entity.
     *
     * @Route("/new", name="cronjob_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cronJob = new Cronjob();
        $form = $this->createForm('Cron\CronBundle\Form\CronJobType', $cronJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cronJob);
            $em->flush($cronJob);

            return $this->redirectToRoute('cronjob_show', array('id' => $cronJob->getId()));
        }

        return $this->render('cronjob/new.html.twig', array(
            'cronJob' => $cronJob,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cronJob entity.
     *
     * @Route("/{id}", name="cronjob_show")
     * @Method("GET")
     */
    public function showAction(CronJob $cronJob)
    {
        $deleteForm = $this->createDeleteForm($cronJob);

        return $this->render('cronjob/show.html.twig', array(
            'cronJob' => $cronJob,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cronJob entity.
     *
     * @Route("/{id}/edit", name="cronjob_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CronJob $cronJob)
    {
        $deleteForm = $this->createDeleteForm($cronJob);
        $editForm = $this->createForm('Cron\CronBundle\Form\CronJobType', $cronJob);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cronjob_edit', array('id' => $cronJob->getId()));
        }

        return $this->render('cronjob/edit.html.twig', array(
            'cronJob' => $cronJob,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cronJob entity.
     *
     * @Route("/{id}", name="cronjob_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CronJob $cronJob)
    {
        $form = $this->createDeleteForm($cronJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cronJob);
            $em->flush();
        }

        return $this->redirectToRoute('cronjob_index');
    }

    /**
     * Creates a form to delete a cronJob entity.
     *
     * @param CronJob $cronJob The cronJob entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CronJob $cronJob)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cronjob_delete', array('id' => $cronJob->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
