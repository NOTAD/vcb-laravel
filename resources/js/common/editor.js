import _ from 'lodash';
import { Router } from './router'

export const Editor = {
    getUrlParam(n) {
        const r = new RegExp("(?:[?&]|&)" + n + "=([^&]+)", "i"),
            o = window.location.search.match(r);
        return o && o.length > 1 ? o[1] : null
    },
    returnFileUrl(n) {
        const r = this.getUrlParam("CKEditorFuncNum");
        window.opener.CKEDITOR.tools.callFunction(r, n);
        window.close();
    },

    initEditor(editor, data) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const options = {
            removePlugins : 'easyimage, cloudservices',
            filebrowserImageBrowseUrl: Router.route('admin.files_index'),
            filebrowserImageUploadUrl: Router.route('admin.ajax_upload_file', { editor: 'ckeditor', _token: token }),
            defaultLanguage: 'vi',
        };
        if (_.isEmpty(window.CKEDITOR.instances[editor])) {
            window.CKEDITOR.replace(editor, options);
        } else {
            window.CKEDITOR.instances[editor].setData(data);
        }
    },
    getEditorData(editor) {
        if (!_.isEmpty(window.CKEDITOR.instances[editor])) {
            return window.CKEDITOR.instances[editor].getData();
        }
        return '';
    },

    browserImage() {
        const url = Router.route('admin.files_index', { selector: 1 });
        const params = 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=1000,height=600';
        window.open(url,'Gallery', params);
    },

};


