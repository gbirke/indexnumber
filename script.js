/**
 * Called by picker buttons to insert text and close the picker again
 *
 * @author Gabriel Birke <gb@birke-software.de>
 */
function indexnumberPickerInsert(text, edid) {
    var rx = new RegExp('<idxnum\\s+' + text + '\\s+#(\\d+)', 'g'),
        editor = document.getElementById(edid),
        maxnumber = 0,
        result;
    while ((result = rx.exec(editor.value)) !== null) {
        maxnumber = Math.max(maxnumber, result[1]);
    }
    maxnumber++;
    insertTags(edid, '<idxnum ' + text + ' #' + maxnumber + ' >', '</idxnum>', '');
    pickerClose();
}

/**
 * Creates a picker window for inserting 
 *
 * @author Gabriel Birke <gb@birke-software.de>
 */
function createIndexnumberPicker(id, list, edid) {

    function getInsertHandler(idxname) {
        return function () {
            indexnumberPickerInsert(idxname, edid);
        };
    }
    var $picker, $btn, i;
    $picker = jQuery('<div class="picker"/>')
        .attr('id', id);

    for (i = 0; i < list.length; i++) {
        $btn = jQuery('<button class="pickerbutton" />')
            .text(list[i])
            .click(getInsertHandler(list[i]));
        $picker.append($btn);
    }
    jQuery("body").append($picker);
    return $picker;
}


/**
 * Add button action for picker buttons and create picker element
 *
 * @param  object     $btn  Button element to add the action to (jQuery object)
 * @param  array      props Associative array of button properties
 * @param  string     edid  ID of the editor textarea
 * @return boolean    If button should be appended
 * @author Gabriel Birke <gb@birke-software.de>
 */
function addBtnActionIndexnumberpicker($btn, props, edid) {
    var pickerid = 'picker' + (pickercounter++);
    createIndexnumberPicker(pickerid, props.list, edid);
    $btn.click(function (evt) {
        pickerToggle(pickerid, $btn);
        evt.preventDefault();
        return false;
    });
    return true;
}


/*jslint plusplus: true, undef: true, sloppy: true, browser: true */
