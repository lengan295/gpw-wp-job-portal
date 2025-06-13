(function () {
    tinymce.PluginManager.add('wpjobportal_searchjob_button', function (editor, url) {
        editor.addButton('wpjobportal_searchjob_button', {
            title: jslang.main_searchjob_title,
            text: jslang.main_searchjob_text,
            icon: 'icon wpjobportal-ouricon-img',
            onclick: function () {
                editor.windowManager.open({
                    title: jslang.window_searchjob_title,
                    width: 500,
                    height: 400,
                    autoScroll: true,
                    classes: 'wpjobportaljobseditorclass',
                    body: [
                        {type: 'textbox', name: 'title', label: jslang.title},
                        {type: 'listbox', name: 'showtitle', label: jslang.showtitle, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'jobtitle', label: jslang.title, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'category', label: jslang.category, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'jobtype', label: jslang.jobtype, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'jobstatus', label: jslang.jobstatus, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'salaryrange', label: jslang.salaryrange, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'shift', label: jslang.shift, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'duration', label: jslang.duration, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'company', label: jslang.company, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'listbox', name: 'address', label: jslang.address, 'values': [{text: 'Show', value: '1'}, {text: 'Hide', value: '0'}]},
                        {type: 'textbox', name: 'columnperrow', label: jslang.columnperrow},
                    ],
                    onsubmit: function (e) {
                        var shcode = makeShortcoldejobsearch(e.data);
                        editor.insertContent(shcode);
                    }
                });
            }
        });
    });
})();

function makeShortcoldejobsearch(data) {
    var c = '[wpjobportal_searchjob';
    if (data.title != '')
        c += ' title="' + data.title + '"';
    c += ' showtitle="' + data.showtitle + '"';
    c += ' jobtitle="' + data.jobtitle + '"';
    c += ' category="' + data.category + '"';
    c += ' jobtype="' + data.jobtype + '"';
    c += ' jobstatus="' + data.jobstatus + '"';
    c += ' salaryrange="' + data.salaryrange + '"';
    c += ' shift="' + data.shift + '"';
    c += ' duration="' + data.duration + '"';
    c += ' company="' + data.company + '"';
    c += ' address="' + data.address + '"';
    if (data.columnperrow != '')
        c += ' columnperrow="' + data.columnperrow + '"';
    c += ']';
    return c;
}
