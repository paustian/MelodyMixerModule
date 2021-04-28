'use strict';

var currentPaustianMelodyMixerModuleEditor = null;
var currentPaustianMelodyMixerModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPaustianMelodyMixerModulePopupAttributes() {
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes';
}

/**
 * Open a popup window with the finder triggered by an editor button.
 */
function PaustianMelodyMixerModuleFinderOpenPopup(editor, editorName) {
    var popupUrl;

    // Save editor for access in selector window
    currentPaustianMelodyMixerModuleEditor = editor;

    popupUrl = Routing.generate('paustianmelodymixermodule_external_finder', { objectType: 'gameScore', editor: editorName });

    if (editorName == 'ckeditor') {
        editor.popup(popupUrl, /*width*/ '80%', /*height*/ '70%', getPaustianMelodyMixerModulePopupAttributes());
    } else {
        window.open(popupUrl, '_blank', getPaustianMelodyMixerModulePopupAttributes());
    }
}


var paustianMelodyMixerModule = {};

paustianMelodyMixerModule.finder = {};

paustianMelodyMixerModule.finder.onLoad = function (baseId, selectedId) {
    if (jQuery('#paustianMelodyMixerModuleSelectorForm').length < 1) {
        return;
    }
    jQuery('select').not("[id$='pasteAs']").change(paustianMelodyMixerModule.finder.onParamChanged);
    
    jQuery('.btn-secondary').click(paustianMelodyMixerModule.finder.handleCancel);

    var selectedItems = jQuery('#paustianmelodymixermoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        paustianMelodyMixerModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

paustianMelodyMixerModule.finder.onParamChanged = function () {
    jQuery('#paustianMelodyMixerModuleSelectorForm').submit();
};

paustianMelodyMixerModule.finder.handleCancel = function (event) {
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        paustianMelodyMixerClosePopup();
    } else if ('quill' === editor) {
        paustianMelodyMixerClosePopup();
    } else if ('summernote' === editor) {
        paustianMelodyMixerClosePopup();
    } else if ('tinymce' === editor) {
        paustianMelodyMixerClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function paustianMelodyMixerGetPasteSnippet(mode, itemId) {
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    if (!itemDescription) {
        itemDescription = itemTitle;
    }
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if ('3' === pasteMode) {
        return '' + itemId;
    }

    // relative link to detail page
    if ('1' === pasteMode) {
        return 'url' === mode ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if ('2' === pasteMode) {
        return 'url' === mode ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    return '';
}


// User clicks on "select item" button
paustianMelodyMixerModule.finder.selectItem = function (itemId) {
    var editor, html;

    html = paustianMelodyMixerGetPasteSnippet('html', itemId);
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        if (null !== window.opener.currentPaustianMelodyMixerModuleEditor) {
            window.opener.currentPaustianMelodyMixerModuleEditor.insertHtml(html);
        }
    } else if ('quill' === editor) {
        if (null !== window.opener.currentPaustianMelodyMixerModuleEditor) {
            window.opener.currentPaustianMelodyMixerModuleEditor.clipboard.dangerouslyPasteHTML(window.opener.currentPaustianMelodyMixerModuleEditor.getLength(), html);
        }
    } else if ('summernote' === editor) {
        if (null !== window.opener.currentPaustianMelodyMixerModuleEditor) {
            if ('3' === jQuery("[id$='pasteAs']").first().val()) {
                window.opener.currentZikulaContentModuleEditor.invoke('insertText', html);
            } else {
                html = jQuery(html).get(0);
                window.opener.currentPaustianMelodyMixerModuleEditor.invoke('insertNode', html);
            }
        }
    } else if ('tinymce' === editor) {
        window.opener.currentPaustianMelodyMixerModuleEditor.insertContent(html);
    } else {
        alert('Insert into Editor: ' + editor);
    }
    paustianMelodyMixerClosePopup();
};

function paustianMelodyMixerClosePopup() {
    window.opener.focus();
    window.close();
}

jQuery(document).ready(function () {
    paustianMelodyMixerModule.finder.onLoad();
});
