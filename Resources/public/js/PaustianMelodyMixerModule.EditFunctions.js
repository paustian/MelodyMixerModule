'use strict';

var editedObjectType;
var editedEntityId;
var editForm;
var formButtons;
var triggerValidation = true;

function paustianMelodyMixerTriggerFormValidation() {
    paustianMelodyMixerExecuteCustomValidationConstraints(editedObjectType, editedEntityId);

    if (!editForm.get(0).checkValidity()) {
        // This does not really submit the form,
        // but causes the browser to display the error message
        editForm.find(':submit').first().click();
    }
}

function paustianMelodyMixerHandleFormSubmit(event) {
    if (triggerValidation) {
        paustianMelodyMixerTriggerFormValidation();
        if (!editForm.get(0).checkValidity()) {
            event.preventDefault();
            return false;
        }
    }

    // hide form buttons to prevent double submits by accident
    formButtons.each(function (index) {
        jQuery(this).addClass('d-none');
    });

    return true;
}

/**
 * Initialises an entity edit form.
 */
function paustianMelodyMixerInitEditForm(mode, entityId) {
    if (jQuery('.paustianmelodymixer-edit-form').length < 1) {
        return;
    }

    editForm = jQuery('.paustianmelodymixer-edit-form').first();
    editedObjectType = editForm.attr('id').replace('EditForm', '');
    editedEntityId = entityId;

    if (jQuery('#moderationFieldsSection').length > 0) {
        jQuery('#moderationFieldsContent').addClass('d-none');
        jQuery('#moderationFieldsSection legend').css({cursor: 'pointer'}).click(function (event) {
            if (jQuery('#moderationFieldsContent').hasClass('d-none')) {
                jQuery('#moderationFieldsContent').removeClass('d-none');
                jQuery(this).find('i').removeClass('fa-expand').addClass('fa-compress');
            } else {
                jQuery('#moderationFieldsContent').addClass('d-none');
                jQuery(this).find('i').removeClass('fa-compress').addClass('fa-expand');
            }
        });
    }

    var allFormFields = editForm.find('input, select, textarea');
    allFormFields.change(function (event) {
        paustianMelodyMixerExecuteCustomValidationConstraints(editedObjectType, editedEntityId);
    });

    formButtons = editForm.find('.form-buttons input');
    if (editForm.find('.btn-danger').length > 0) {
        editForm.find('.btn-danger').first().bind('click keypress', function (event) {
            if (!window.confirm(Translator.trans('Do you really want to delete this entry?'))) {
                event.preventDefault();
            }
        });
    }
    editForm.find('button[type=submit]').bind('click keypress', function (event) {
        triggerValidation = !jQuery(this).attr('formnovalidate');
    });
    editForm.submit(paustianMelodyMixerHandleFormSubmit);

    if ('create' !== mode) {
        paustianMelodyMixerTriggerFormValidation();
    }
}

jQuery(document).ready(function () {
    if (jQuery('.field-editing-definition').length > 0) {
        jQuery('.field-editing-definition').each(function (index) {
            if ('user' === jQuery(this).data('field-type')) {
                initUserLiveSearch(jQuery(this).data('field-name'));
            } else if ('date' === jQuery(this).data('field-type')) {
                paustianMelodyMixerInitDateField(jQuery(this).data('field-name'));
            } else if ('upload' === jQuery(this).data('field-type')) {
                paustianMelodyMixerInitUploadField(jQuery(this).data('field-name'));
            }
        });
    }
    if (jQuery('#formEditingDefinition').length > 0) {
        paustianMelodyMixerInitEditForm(jQuery('#formEditingDefinition').data('mode'), jQuery('#formEditingDefinition').data('entityid'));
    }
});
