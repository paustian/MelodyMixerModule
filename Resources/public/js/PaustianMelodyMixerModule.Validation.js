'use strict';

function paustianMelodyMixerValidateNoSpace(val) {
    var valStr;

    valStr = '' + val;

    return -1 === valStr.indexOf(' ');
}

/**
 * Runs special validation rules.
 */
function paustianMelodyMixerExecuteCustomValidationConstraints(objectType, currentEntityId) {
}
