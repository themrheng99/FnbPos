// ================================================================================================
// Updated: 2020-04-10 (for assignment purpose, use the latest version)
// ================================================================================================

// functions --------------------------------------------------------------------------------------

// escape regular expression
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// onload -----------------------------------------------------------------------------------------
$(function () {

    // send GET request to server
    $(document).on('click', '[data-get]', function (e) {
        e.preventDefault();
        location = $(this).data('get') || location.pathname;
    });

    // send POST request to server
    $(document).on('click', '[data-post]', function (e) {
        e.preventDefault();
        $('<form>')
            .prop('method', 'post')
            .prop('action', $(this).data('post'))
            .appendTo(document.body)
            .submit();
    });

    // reset --> reload the page (retain GET parameters)
    $('[type=reset]').on('click', function (e) {
        e.preventDefault();
        location = location;
    });

    // auto convert to uppercase
    $('[data-upper]').on('input', function (e) {
        e.preventDefault();
        let a = this.selectionStart;
        let b = this.selectionEnd;
        this.value = this.value.toUpperCase();
        this.setSelectionRange(a, b);
    });

    // check all checkboxes match the given name
    $('[data-check]').on('click', function (e) {
        e.preventDefault();
        let name = $(this).data('check');
        $(`[name='${name}']`)
            .prop('checked', true)
            .trigger('change');
    });

    // uncheck all checkboxes match the given name
    $('[data-uncheck]').on('click', function (e) {
        e.preventDefault();
        let name = $(this).data('uncheck');
        $(`[name='${name}']`)
            .prop('checked', false)
            .trigger('change');
    });

    // click on the first checkbox of the row
    $('[data-row-check]').on('click', function (e) {
        if ($(e.target).is(':input')) return;

        let cb = $(this).find(':checkbox')[0];
        if (cb) {
            cb.checked = !cb.checked;
            $(cb).trigger('change');
        }
    });

    // click to download the file from the URL
    $('[data-download]').on('click', function (e) {
        e.preventDefault();
        let a = $('<a>')[0];
        a.href = $(this).data('download');
        a.download = '';
        a.click();
    });

    // *** NEW ***
    // store original preview image's src in data-src
    $('[data-preview] img').each(function () {
        // jQuery = $(this).prop('src', $(this).data('src'));
        this.dataset.src = this.src;
    });

    // *** NEW ***
    // display preview image if a file is choosen (and valid)
    $('[data-preview] input').on('change', function () {
        let img = $(this).siblings('img')[0]; // get img as DOM object
        let f = this.files[0]; // get file
        if ($(this).valid() && f) {
            // if the input is valid and a file is selected
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
        }
    });

    // ... more
    
});

// ================================================================================================
// VALIDATION SETTINGS
// ================================================================================================

// help client-side validation to locate error message location
// example: <span id='id-error' class='error'><span>
$.validator.setDefaults({
    errorElement: 'span',
    errorPlacement(error) {
        $(`[id='${error[0].id}']`).replaceWith(error);
    },
});

// disable attribute rules
$.validator.attributeRules = () => {};

// process server-side validation rules before used at client-side
// ignore validation methods that are not supported at client-side
function processRules(rules) {
    let result = {};
    for (let [key, rule] of Object.entries(rules)) {
        result[key] = {};
        for (let [method, param] of Object.entries(rule)) {
            if (method in $.validator.methods) {
                // *** NEW ***
                if (method.match('equalTo|notEqualTo|lessThan|lessThanEqual|greaterThan|greaterThanEqual')) {
                    param = '#' + param;
                }
                result[key][method] = param;
            }
        }
    }
    return result;
}

// ================================================================================================
// VALIDATION METHODS
// ================================================================================================

$.validator.addMethod('unique', function (value, element, param) {
    return this.optional(element) || param.findIndex(v => v == value) == -1;
}, 'Value not unique');

$.validator.addMethod('exist', function (value, element, param) {
    return this.optional(element) || param.findIndex(v => v == value) >= 0;
}, 'Value not exist');

// ... more