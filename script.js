/**
 * Called by picker buttons to insert text and close the picker again
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Gabriel Birke <gb@birke-software.de>
 */
function indexnumberPickerInsert(text,edid){
    var rx = new RegExp('<idxnum\\s+'+text+'\\s+#(\\d+)', 'g'),
        editor = document.getElementById(edid),
        maxnumber = 0,
        result
    ;
    while((result = rx.exec(editor.value)) !== null) {
        maxnumber = Math.max(maxnumber, result[1])
    }
    maxnumber++;
    insertTags(edid,'<idxnum '+text+' #'+maxnumber+' >', '</idxnum>', '');
    pickerClose();
}

/**
 * Creates a picker window for inserting 
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Gabriel Birke <gb@birke-software.de>
 */
function createIndexnumberPicker(id,list, edid){
  
  function getInsertHandler(idxname) {
    return function(){  
          indexnumberPickerInsert(id, idxname, edid);
    };
  }
    var picker, btn, i, body;
    picker               = document.createElement('div');
    picker.className         = 'picker';
    picker.id                = id;
    picker.style.position    = 'absolute';
    picker.style.marginLeft  = '-10000px';

    for(i=0;i<list.length;i++){
        btn = document.createElement('button');
        btn.className = 'pickerbutton';
        btn.textContent = list[i];
        addEvent(btn,'click', getInsertHandler(list[i]));
        picker.appendChild(btn); 
    }
    
    body = document.getElementsByTagName('body')[0];
    body.appendChild(picker);
    return picker;
}


/**
 * Add button action for picker buttons and create picker element
 *
 * @param  DOMElement btn   Button element to add the action to 
 * @param  array      props Associative array of button properties
 * @param  string     edid  ID of the editor textarea
 * @param  int        id    Unique number of the picker
 * @return boolean    If button should be appended
 * @author Gabriel Birke <gb@birke-software.de>
 */
function addBtnActionIndexnumberpicker(btn, props, edid, id)
{
    var pickerid = 'picker'+(pickercounter++);
    createIndexnumberPicker(pickerid,
         props.list,
         edid
    );
    addEvent(btn, 'click', function(){
        pickerToggle(pickerid, btn);
        return false;
    });
    return true;
    
}



