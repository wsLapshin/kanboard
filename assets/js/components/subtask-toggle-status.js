KB.on('dom.ready', function () {
    $(document).on('click', '.js-subtask-toggle-status', function(e) {
        var el = $(this);
        var url = el.attr('href');

        e.preventDefault();

        $.ajax({
            cache: false,
            url: url,
            success: function(data) {
                 //CDEV
                /*
                 * @task 
                 * move this js change to Timetrackingeditor plugin
                 */
                window.location.href = window.location.href;
                
                /*if (url.indexOf('fragment=table') != -1) {
                    $('.subtasks-table').replaceWith(data);
                } else if (url.indexOf('fragment=rows') != -1) {
                    $(el).closest('.task-list-subtasks').replaceWith(data);
                } else {
                    $(el).closest('.subtask-title').replaceWith(data);
                }*/
            }
        });
    });

    $(document).on('click', '.js-subtask-toggle-timer', function(e) {
        var el = $(this);
        e.preventDefault();

        $.ajax({
            cache: false,
            url: el.attr('href'),
            success: function(data) {
                 //CDEV
                /*
                 * @task 
                 * move this js change to Timetrackingeditor plugin
                 */
                window.location.href = window.location.href;
                //$(el).closest('.subtask-time-tracking').replaceWith(data);
            }
        });
    });
});
