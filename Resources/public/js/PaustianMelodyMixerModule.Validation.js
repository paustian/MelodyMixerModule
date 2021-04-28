'use strict';

var lastGameScorePlayerUid = '';

/**
 * Performs a duplicate check for unique fields
 */
function paustianMelodyMixerUniqueCheck(elem, excludeId) {
    var objectType, fieldName, fieldValue, result, params;

    objectType = elem.attr('id').split('_')[1];
    fieldName = elem.attr('id').split('_')[2];
    fieldValue = elem.val();
    if (fieldValue == window['last' + paustianMelodyMixerCapitaliseFirstLetter(objectType) + paustianMelodyMixerCapitaliseFirstLetter(fieldName)]) {
        return true;
    }

    window['last' + paustianMelodyMixerCapitaliseFirstLetter(objectType) + paustianMelodyMixerCapitaliseFirstLetter(fieldName)] = fieldValue;

    result = true;
    params = {
        ot: encodeURIComponent(objectType),
        fn: encodeURIComponent(fieldName),
        v: encodeURIComponent(fieldValue),
        ex: excludeId
    };

    jQuery.ajax({
        url: Routing.generate('paustianmelodymixermodule_ajax_checkforduplicate'),
        method: 'GET',
        dataType: 'json',
        async: false,
        data: params
    }).done(function (data) {
        if (null == data || true === data.isDuplicate) {
            result = false;
        }
    });

    return result;
}

function paustianMelodyMixerValidateNoSpace(val) {
    var valStr;

    valStr = '' + val;

    return -1 === valStr.indexOf(' ');
}

/**
 * Runs special validation rules.
 */
function paustianMelodyMixerExecuteCustomValidationConstraints(objectType, currentEntityId) {
    jQuery('.validate-unique').each(function () {
        if (!paustianMelodyMixerUniqueCheck(jQuery(this), currentEntityId)) {
            jQuery(this).get(0).setCustomValidity(Translator.trans('This value is already assigned, but must be unique. Please change it.', {}, 'validators'));
        } else {
            jQuery(this).get(0).setCustomValidity('');
        }
    });
}
