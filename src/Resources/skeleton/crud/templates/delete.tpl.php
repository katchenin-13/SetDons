{% block page_content %}
    {% form_theme form 'widget/fields-block.html.twig' %}
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suppression</h5>
        <div class="btn btn-icon btn-sm  ms-2" data-bs-dismiss="modal" aria-label="Close">
            <span class="svg-icon svg-icon-2x text-white">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
					<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
				</svg>
            </span>
        </div>
    </div>
    {{ form_start(form, {'attr': {'class':'form-horizontal', 'role':'form'}}) }}
        <div class="modal-body">
             {{ include('_includes/ajax/response.html.twig') }}
             <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Voulez-vous vraiment supprimer cet enregistrement?</div>
            </div>
            {{ form_widget(form) }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn  btn-default btn-sm" data-bs-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-main btn-ajax btn-sm"><span class="spinner-border d-none  spinner-ajax spinner-border-sm" role="status" aria-hidden="true"></span> Supprimer</button>
        </div>
    {{ form_end(form) }}
{% endblock %}

{% block java %}
    <script>
    </script>
{% endblock %}