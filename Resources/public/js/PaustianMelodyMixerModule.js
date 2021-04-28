'use strict';

function paustianMelodyMixerCapitaliseFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Initialise the quick navigation form in list views.
 */
function paustianMelodyMixerInitQuickNavigation() {
    var quickNavForm;
    var objectType;

    if (jQuery('.paustianmelodymixermodule-quicknav').length < 1) {
        return;
    }

    quickNavForm = jQuery('.paustianmelodymixermodule-quicknav').first();
    objectType = quickNavForm.attr('id').replace('paustianMelodyMixerModule', '').replace('QuickNavForm', '');

    var quickNavFilterTimer;
    quickNavForm.find('select').change(function (event) {
        clearTimeout(quickNavFilterTimer);
        quickNavFilterTimer = setTimeout(function() {
            quickNavForm.submit();
        }, 5000);
    });

    var fieldPrefix = 'paustianmelodymixermodule_' + objectType.toLowerCase() + 'quicknav_';
    // we can hide the submit button if we have no visible quick search field
    if (jQuery('#' + fieldPrefix + 'q').length < 1 || jQuery('#' + fieldPrefix + 'q').parent().parent().hasClass('d-none')) {
        jQuery('#' + fieldPrefix + 'updateview').addClass('d-none');
    }
}

/**
 * Simulates a simple alert using bootstrap.
 */
function paustianMelodyMixerSimpleAlert(anchorElement, title, content, alertId, cssClass) {
    var alertBox;

    alertBox = ' \
        <div id="' + alertId + '" class="alert alert-' + cssClass + ' fade show"> \
          <button type="button" class="close" data-dismiss="alert">&times;</button> \
          <h4>' + title + '</h4> \
          <p>' + content + '</p> \
        </div>';

    // insert alert before the given anchor element
    anchorElement.before(alertBox);

    jQuery('#' + alertId).delay(200).addClass('in').fadeOut(4000, function () {
        jQuery(this).remove();
    });
}

/**
 * Initialises the mass toggle functionality for admin view pages.
 */
function paustianMelodyMixerInitMassToggle() {
    if (jQuery('.paustianmelodymixer-mass-toggle').length > 0) {
        jQuery('.paustianmelodymixer-mass-toggle').unbind('click').click(function (event) {
            jQuery('.paustianmelodymixer-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
        });
    }
}

/**
 * Creates a dropdown menu for the item actions.
 */
function paustianMelodyMixerInitItemActions(context) {
    var containerSelector;
    var containers;
    
    containerSelector = '';
    if ('view' === context) {
        containerSelector = '.paustianmelodymixermodule-view';
    } else if ('display' === context) {
        containerSelector = 'h2, h3';
    }
    
    if ('' === containerSelector) {
        return;
    }
    
    containers = jQuery(containerSelector);
    if (containers.length < 1) {
        return;
    }
    
    containers.find('.dropdown > ul').removeClass('nav').addClass('list-unstyled dropdown-menu');
    containers.find('.dropdown > ul > li').addClass('dropdown-item').css('padding', 0);
    containers.find('.dropdown > ul a').addClass('d-block').css('padding', '3px 5px');
    containers.find('.dropdown > ul a i').addClass('fa-fw mr-1');
    if (containers.find('.dropdown-toggle').length > 0) {
        containers.find('.dropdown-toggle').removeClass('d-none').dropdown();
    }
}

jQuery(document).ready(function () {
    var isViewPage;
    var isDisplayPage;

    isViewPage = jQuery('.paustianmelodymixermodule-view').length > 0;
    isDisplayPage = jQuery('.paustianmelodymixermodule-display').length > 0;

    if (isViewPage) {
        paustianMelodyMixerInitQuickNavigation();
        paustianMelodyMixerInitMassToggle();
        paustianMelodyMixerInitItemActions('view');
    } else if (isDisplayPage) {
        paustianMelodyMixerInitItemActions('display');
    }
});
