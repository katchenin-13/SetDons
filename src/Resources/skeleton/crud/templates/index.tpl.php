{% extends 'base-layout.html.twig' %}

{% block title %}Liste des <?= $entity_twig_var_plural ?>{% endblock %}
{% block header %}<?= $entity_twig_var_plural ?>{% endblock %}
{% block breadcrumb %}{% endblock %}
{% block body %}
<div class="card shadow-sm">
    <div class="card-header card-header-sm">
        <h3 class="card-title"> Liste des <?= $entity_twig_var_plural ?></h3>
        <div class="card-toolbar">
            <a href="{{ path('<?= $route_name ?>_new') }}" class="btn btn-main btn-sm"  
                data-bs-toggle="modal" data-bs-target="#exampleModalSizeLg2">
                <i class="bi bi-plus-square text-light"></i>
                Nouveau
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div id="grid_<?= $route_name ?>" class="grid-dt-wrapper">Chargement....</div>
            </div>
        </div>
    </div>
</div>
{% endblock %}


{% block java %}
    <script src="{{ asset('assets/js/datatables.js') }}"></script>
    <script> 
        $(function() { 
              $('#grid_<?= $route_name ?>').initDataTables({{ datatable_settings(datatable) }}, {
                  searching: true,
                  ajaxUrl: "{{ path('<?= $route_name ?>_index') }}",
                  language: {
                      url: asset_base_path + "/js/i18n/French.json"
                  }
              });
        });
    </script>
{% endblock %}
