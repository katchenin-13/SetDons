<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

#[Route('<?= $route_path ?>')]
class <?= $class_name ?> extends AbstractController
{
<?= $generator->generateRouteForControllerMethod('/', sprintf('%s_index', $route_name), ['GET', 'POST']) ?>
<?php if (isset($repository_full_class_name)): ?>
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
        ->createAdapter(ORMAdapter::class, [
            'entity' => <?= $entity_class_name ?>::class,
        ])
        ->setName('dt_<?= $route_name ?>');

        $renders = [
            'edit' =>  new ActionRender(function () {
                return true;
            }),
            'delete' => new ActionRender(function () {
                return true;
            }),
        ];


        $hasActions = false;

        foreach ($renders as $_ => $cb) {
            if ($cb->execute()) {
                $hasActions = true;
                break;
            }
        }

        if ($hasActions) {
            $table->add('id', TextColumn::class, [
                'label' => 'Actions'
                , 'orderable' => false
                ,'globalSearchable' => false
                ,'className' => 'grid_row_actions'
                , 'render' => function ($value, <?= $entity_class_name ?> $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('<?= $route_name ?>_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('<?= $route_name ?>_delete', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-trash'
                            , 'attrs' => ['class' => 'btn-main']
                            ,  'render' => $renders['delete']
                        ]
                    ]

                    ];
                    return $this->renderView('_includes/default_actions.html.twig', compact('options', 'context'));
                }
            ]);
        }


        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }


        return $this->render('<?= $templates_path ?>/index.html.twig', [
            'datatable' => $table
        ]);
    }
<?php else: ?>
    public function index(EntityManagerInterface $entityManager): Response
    {
        $<?= $entity_var_plural ?> = $entityManager
            ->getRepository(<?= $entity_class_name ?>::class)
            ->findAll();

        return $this->render('<?= $templates_path ?>/index.html.twig', [
            '<?= $entity_twig_var_plural ?>' => $<?= $entity_var_plural ?>,
        ]);
    }
<?php endif ?>

<?= $generator->generateRouteForControllerMethod('/new', sprintf('%s_new', $route_name), ['GET', 'POST']) ?>
<?php if (isset($repository_full_class_name) && $generator->repositoryHasSaveAndRemoveMethods($repository_full_class_name)) { ?>
    public function new(Request $request, <?= $repository_class_name ?> $<?= $repository_var ?>, FormError $formError): Response
<?php } else { ?>
    public function new(Request $request, EntityManagerInterface $entityManager, FormError $formError): Response
<?php } ?>
    {
        $<?= $entity_var_singular ?> = new <?= $entity_class_name ?>();
        $form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>, [
            'method' => 'POST',
            'action' => $this->generateUrl('<?= $route_name ?>_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

<?php if (isset($repository_full_class_name) && $generator->repositoryHasSaveAndRemoveMethods($repository_full_class_name)) { ?>
        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('<?= $route_name ?>_index');




            if ($form->isValid()) {

                $<?= $repository_var ?>->save($<?= $entity_var_singular ?>, true);
                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                if (!$isAjax) {
                  $this->addFlash('warning', $message);
                }

            }


            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }


        }
<?php } else { ?>
        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('<?= $route_name ?>_index');




            if ($form->isValid()) {

                $entityManager->persist($<?= $entity_var_singular ?>);
                $entityManager->flush();

                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = 500;
                if (!$isAjax) {
                  $this->addFlash('warning', $message);
                }

            }


            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }


        }
<?php } ?>

<?php if ($use_render_form) { ?>
        return $this->renderForm('<?= $templates_path ?>/new.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form,
        ]);
<?php } else { ?>
        return $this->render('<?= $templates_path ?>/new.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form->createView(),
        ]);
<?php } ?>
    }

<?= $generator->generateRouteForControllerMethod(sprintf('/{%s}/show', $entity_identifier), sprintf('%s_show', $route_name), ['GET']) ?>
    public function show(<?= $entity_class_name ?> $<?= $entity_var_singular ?>): Response
    {
        return $this->render('<?= $templates_path ?>/show.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
        ]);
    }

<?= $generator->generateRouteForControllerMethod(sprintf('/{%s}/edit', $entity_identifier), sprintf('%s_edit', $route_name), ['GET', 'POST']) ?>
<?php if (isset($repository_full_class_name) && $generator->repositoryHasSaveAndRemoveMethods($repository_full_class_name)) { ?>
    public function edit(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, <?= $repository_class_name ?> $<?= $repository_var ?>, FormError $formError): Response
<?php } else { ?>
    public function edit(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, EntityManagerInterface $entityManager, FormError $formError): Response
<?php } ?>
    {

        $form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>, [
            'method' => 'POST',
            'action' => $this->generateUrl('<?= $route_name ?>_edit', [
                    '<?= $entity_identifier ?>' =>  $<?= $entity_var_singular ?>->get<?= ucfirst($entity_identifier) ?>()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

<?php if (isset($repository_full_class_name) && $generator->repositoryHasSaveAndRemoveMethods($repository_full_class_name)) { ?>
        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('<?= $route_name ?>_index');


            if ($form->isValid()) {

                $<?= $repository_var ?>->save($<?= $entity_var_singular ?>, true);
                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                if (!$isAjax) {
                  $this->addFlash('warning', $message);
                }

            }


            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }
        }
<?php } else { ?>
       if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('<?= $route_name ?>_index');




            if ($form->isValid()) {

                $entityManager->persist($<?= $entity_var_singular ?>);
                $entityManager->flush();

                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = 500;
                if (!$isAjax) {
                  $this->addFlash('warning', $message);
                }

            }

            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }

        }
<?php } ?>

<?php if ($use_render_form) { ?>
        return $this->renderForm('<?= $templates_path ?>/edit.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form,
        ]);
<?php } else { ?>
        return $this->render('<?= $templates_path ?>/edit.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form->createView(),
        ]);
<?php } ?>
    }

<?= $generator->generateRouteForControllerMethod(sprintf('/{%s}/delete', $entity_identifier), sprintf('%s_delete', $route_name), ['DELETE', 'GET']) ?>
<?php if (isset($repository_full_class_name) && $generator->repositoryHasSaveAndRemoveMethods($repository_full_class_name)) { ?>
    public function delete(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, <?= $repository_class_name ?> $<?= $repository_var ?>): Response
<?php } else { ?>
    public function delete(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, EntityManagerInterface $entityManager): Response
<?php } ?>
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                '<?= $route_name ?>_delete'
                ,   [
                        '<?= $entity_identifier ?>' => $<?= $entity_var_singular ?>->get<?= ucfirst($entity_identifier) ?>()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
<?php if (isset($repository_full_class_name) && $generator->repositoryHasSaveAndRemoveMethods($repository_full_class_name)) { ?>
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $<?= $repository_var ?>->remove($<?= $entity_var_singular ?>, true);

            $redirect = $this->generateUrl('<?= $route_name ?>_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
                'data' => $data
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return $this->json($response);
            }
        }
<?php } else { ?>
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $entityManager->remove($<?= $entity_var_singular ?>);
            $entityManager->flush();

            $redirect = $this->generateUrl('<?= $route_name ?>_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
                'data' => $data
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return $this->json($response);
            }
        }
<?php } ?>

        return $this->renderForm('<?= $templates_path ?>/delete.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form,
        ]);
    }
}
