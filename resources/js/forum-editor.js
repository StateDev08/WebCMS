import EasyMDE from 'easymde';
import $ from 'jquery';
import sceditorContentStyle from 'sceditor/minified/themes/content/default.min.css?url';

window.$ = $;
window.jQuery = $;

const loadSceditor = async () => {
    await import('sceditor/minified/sceditor.min.js');
    await import('sceditor/minified/formats/bbcode.js');
    return window.sceditor;
};

const initForumEditor = async () => {
    const form = document.querySelector('[data-forum-editor]');
    if (!form) {
        return;
    }

    const formatInput = form.querySelector('#content_format');
    const markdownContainer = form.querySelector('#markdown-editor-container');
    const bbcodeContainer = form.querySelector('#bbcode-editor-container');
    const markdownTextarea = form.querySelector('#markdown-editor');
    const bbcodeTextarea = form.querySelector('#bbcode-editor');
    const toggleButtons = form.querySelectorAll('[data-format-toggle]');

    if (!formatInput || !markdownTextarea || !bbcodeTextarea) {
        return;
    }

    let activeFormat = form.dataset.initialFormat || formatInput.value || 'markdown';
    const easyMde = new EasyMDE({
        element: markdownTextarea,
        status: false,
        spellChecker: false,
    });

    const sceditor = await loadSceditor();
    const sceditorInstance = sceditor
        ? sceditor.create(bbcodeTextarea, {
            format: 'bbcode',
            style: sceditorContentStyle,
        })
        : null;

    const syncToTextarea = () => {
        if (activeFormat === 'markdown') {
            markdownTextarea.value = easyMde.value();
        } else if (sceditorInstance) {
            bbcodeTextarea.value = sceditorInstance.val();
        }
    };

    const setEditorValues = (value) => {
        easyMde.value(value);
        if (sceditorInstance) {
            sceditorInstance.val(value);
        } else {
            bbcodeTextarea.value = value;
        }
    };

    const setActiveFormat = (format) => {
        syncToTextarea();
        const currentValue = activeFormat === 'markdown'
            ? easyMde.value()
            : (sceditorInstance ? sceditorInstance.val() : bbcodeTextarea.value);

        activeFormat = format;
        formatInput.value = format;

        if (activeFormat === 'markdown') {
            markdownTextarea.setAttribute('name', 'content_original');
            bbcodeTextarea.removeAttribute('name');
            markdownContainer.classList.remove('is-hidden');
            bbcodeContainer.classList.add('is-hidden');
        } else {
            bbcodeTextarea.setAttribute('name', 'content_original');
            markdownTextarea.removeAttribute('name');
            bbcodeContainer.classList.remove('is-hidden');
            markdownContainer.classList.add('is-hidden');
        }

        setEditorValues(currentValue);
    };

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const format = button.dataset.formatToggle;
            if (format && format !== activeFormat) {
                setActiveFormat(format);
            }
        });
    });

    form.addEventListener('submit', () => {
        syncToTextarea();
    });

    setActiveFormat(activeFormat);
};

document.addEventListener('DOMContentLoaded', initForumEditor);
