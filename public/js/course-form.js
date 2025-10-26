$(function () {
    let moduleIndex = 0;

    function addModule(idx = null) {
        const index = (idx === null) ? moduleIndex++ : idx;
        let tpl = $('#module-template').html();
        tpl = tpl.replace(/__MODULE_INDEX__/g, index).replace(/__MODULE_NUMBER__/g, index + 1);
        const $node = $(tpl);
        // add listeners
        $node.find('.toggle-module').on('click', function () {
            $node.find('.module-body').slideToggle();
        });
        $node.find('.remove-module').on('click', function () {
            if (confirm('Remove this module?')) $node.remove();
        });
        $node.find('.add-content').on('click', function () {
            addContent(index, $node.find('.module-contents'));
        });

        $('#modules-wrapper').append($node);
    }

    function addContent(moduleIdx, $contentsWrapper = null) {
        // find module element
        const $moduleBox = $(`[data-module-index="${moduleIdx}"]`);
        if (!$moduleBox.length) return;
        const $wrapper = $contentsWrapper ? $contentsWrapper : $moduleBox.find('.module-contents');
        const contentIndex = $wrapper.find('.content-box').length;
        let tpl = $('#content-template').html();
        tpl = tpl.replace(/__MODULE_INDEX__/g, moduleIdx)
            .replace(/__CONTENT_INDEX__/g, contentIndex)
            .replace(/__CONTENT_NUMBER__/g, contentIndex + 1);
        const $node = $(tpl);

        // change event for content type
        $node.find('.content-type').on('change', function () {
            const val = $(this).val();
            $node.find('.field-text').hide();
            $node.find('.field-file').hide();
            $node.find('.field-url').hide();
            if (val === 'text') $node.find('.field-text').show();
            if (val === 'image' || val === 'video') $node.find('.field-file').show();
            if (val === 'link') $node.find('.field-url').show();
        });

        $node.find('.remove-content').on('click', function () {
            if (confirm('Remove this content?')) $node.remove();
        });

        $wrapper.append($node);
    }

    // initial one module with one content
    addModule();
    addContent(0);

    $('#add-module-btn').on('click', function () { addModule(); });

    // Optional: intercept submit to show a confirmation
    $('#course-form').on('submit', function () {
        // simple client-side check: at least one module with title
        let valid = true;
        $('input[name^="modules"]').each(function () {
            // nothing required here, server validates
        });
        return valid; // allow submit
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
