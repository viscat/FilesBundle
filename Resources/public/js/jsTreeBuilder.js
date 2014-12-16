$(document).ready(function () {

    $('#jstree1').jstree({
        'core' : {
            'data' : {
                'url' : 'getNode',
                'data' : function (node) {
                    return { 'id' : node.id };
                }
            },
            'themes' : {
                'responsive' : true,
                'variant' : 'small',
                'stripes' : true
            }
        },
        'sort' : function(a, b) {
            return this.get_type(a) === this.get_type(b) ?
                        (this.get_text(a) > this.get_text(b) ? 1 : -1)
                        : (this.get_type(a) <= this.get_type(b) ? 1 : -1);
        },
        'types' : {
            'folder' : { 'icon' : 'folder' },
            'file' : { 'valid_children' : [], 'icon' : 'glyphicon glyphicon-flash' }
        },
        'unique' : {
            'duplicate' : function (name, counter) {
                return name + ' ' + counter;
            }
        },
        'plugins' : ['sort','types','unique','search','wholerow']
    })
    .on('changed.jstree', function (e, data) {
        if(data && data.selected && data.selected.length) {
            $.get('getNodeContent/' + data.selected, function (d) {
                if(d && typeof d.type !== 'undefined') {
                    console.log(d.content);
                }
            });
        }
    });

    var to = false;
    $('#jsTreeSearchField').keyup(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
          var v = $('#jsTreeSearchField').val();
          $('#jstree1').jstree(true).search(v);
      }, 250);
    });

});