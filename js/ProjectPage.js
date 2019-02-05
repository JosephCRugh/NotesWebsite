var noteIdToDelete = -1;
var currentNoteId = 0;

class Note {

  constructor(noteId, noteDiv, inputTitle, headerTitle) {
    this.noteId = noteId;
    this.isInputTitle = false;
    this.inputTitle = inputTitle;
    this.headerTitle = headerTitle;
    this.noteDiv = noteDiv;
    this.previousTitle = headerTitle.text();

    noteDiv.draggable({
      containment: "#notes-container",
      stop: function(event, ui) {

        $.post('backend/ChangeNotePosition.php', {
          projectName: $('title').text(),
          noteId: noteId,
          posX: $(this).position().left,
          posY: $(this).position().top
        });
      }
    });
  }

  getPreviousTitle() {
    return this.previousTitle;
  }

  setPreviousTitle(title) {
    this.previousTitle = title;
  }

  getInputTitle() {
    return this.inputTitle;
  }

  getHeaderTitle() {
    return this.headerTitle;
  }

  getNoteDiv() {
    return this.noteDiv;
  }

  getNoteId() {
    return this.noteId;
  }

  getIsInputTitle() {
    return this.isInputTitle;
  }

  setIsInputTitle(tof) {
    this.isInputTitle = tof;
  }
}

$(document).ready(function() {

  $('.notes-style').each(function (noteDiv) {
    $(this).find('div input').hide();
  });

  searchForNotes();

  // No reason to let users that don't have access to editing,
  // perform edit actions.
  if ($('body').attr('value').split('-')[3] == "false") {
    return;
  }

  setupNodes();

  addNotes();

  deleteNotes();

});

function searchForNotes() {

  $('#note-search-input').on('input', function() {
    var searchValue = $(this).val();

    if (searchValue === "") {
      $('.notes-style').each(function() { $(this).css("background", "white"); });
      return;
    }

    $('.notes-style').each(function() {

      if ($(this).find('div h3').text().search(searchValue) !== -1) {
        $(this).css("background", "rgb(157, 182, 224)");
      } else {
        $(this).css("background", "white");
      }

    });
  });

}

function setupNodes() {

  $('.notes-style').each(function () {
      var noteId = parseInt($(this).attr('id').split('-')[1]);
      performNoteActions(new Note(noteId, $(this), $(this).find('div input'), $(this).find('div h3')));
      currentNoteId = noteId + 1;
  });
}

function addNotes() {

  $('#add-note-button').click(function() {

    var noteDiv = $('<div class="notes-style">' +
      '<div>' +
        '<h3>Title</h3>' +
        '<span name="title-edit" class="glyphicon glyphicon-pencil"></span>' +
        '<input type="text"></input>' +
      '</div>' +
      '<textarea class="form-control z-depth-1"></textarea>' +
      '<div class="notes-bottom">' +
        '<button type="text" class="btn btn-primary" style="width: 100%;" hidden>Finish Edit</button>' +
        '<span name="notes-delete" class="glyphicon glyphicon-trash"></span>' +
      '</div>' +
    '</div>');

    $('#notes-container').append(noteDiv);
    let note = new Note(currentNoteId, noteDiv, noteDiv.find('div input'), noteDiv.find('div h3'));
    noteDiv.attr('id', 'note-' + currentNoteId);

    performNoteActions(note);

    $.post("backend/AddNote.php", {
      projectName: $('title').text(),
      title: "Title",
      posX: noteDiv.position().left,
      posY: noteDiv.position().top
    });

    findCurrentId();
  });
}

function deleteNotes() {

  $('#delete-option').hide();

  $('#delete-option button[name="no-delete"]').click(function() {
    $('#delete-option').hide();
    $('.gray-overlay').hide();
  });

  $('#delete-option button[name="do-delete"]').click(function() {
    $('#notes-container').find('#note-' + noteIdToDelete).remove();
    $('#delete-option').hide();
    $('.gray-overlay').hide();

    // Telling the server to delete the note.
    $.post("backend/DeleteNote.php", {
      projectName: $('title').text(),
      noteId: noteIdToDelete
    });

    findCurrentId();
  });
}

function performNoteActions(note) {

  var noteDiv = note.getNoteDiv();

  processNoteContentEdit(note, noteDiv);

  processNotePopupDltOpts(note, noteDiv);

  performNoteInputTitleKeyUp(note, noteDiv);

  performEditNoteTitle(note, noteDiv);

  noteContentFocus(noteDiv);

}

function noteContentFocus(noteDiv) {
  noteDiv.find('textarea').focusin(function() {
    noteDiv.find('div button').removeAttr('hidden');
    noteDiv.find('div button').show();
    noteDiv.find('.notes-bottom').css('height', '42px');
  });
}

function processNoteContentEdit(note, noteDiv) {

  var contentEditCallback = function() {
    var content = noteDiv.find('textarea').val();

    if (content.length > 500) {
        noteDiv.find('textarea').focus();
        return;
    }

    noteDiv.find('.notes-bottom').css('height', '20px');
    noteDiv.find('button').hide();

    $.post('backend/ChangeNoteContent.php', {
      projectName: $('title').text(),
      noteId: note.getNoteId(),
      content: content
    });
  };

  noteDiv.find('textarea').focusout(contentEditCallback);

  noteDiv.find('button').click(contentEditCallback);
}

function processNotePopupDltOpts(note, noteDiv) {
  noteDiv.find('div span[name="notes-delete"]').click(function() {
    $('#delete-option').removeAttr('hidden');
    $('#delete-option').show();
    $('.gray-overlay').removeAttr('hidden');
    $('.gray-overlay').show();
    noteIdToDelete = note.getNoteId();
  });
}

function performNoteInputTitleKeyUp(note, noteDiv) {
  note.getInputTitle().on('keyup', function(event) {
    $(this).removeClass('is-invalid')
    if (event.keyCode == 13) {
      note.setIsInputTitle(false);
      if (processNoteTitleChange(note, noteDiv)) {
        toggleNoteTitleInput(note);
      }
    }
  });
}

function performEditNoteTitle(note, noteDiv) {
  noteDiv.find('div span[name="title-edit"]').click(function() {
    if (note.getIsInputTitle()) {
      if (!processNoteTitleChange(note, noteDiv)) {
        return;
      }
    }
    note.setIsInputTitle(!note.getIsInputTitle());

    note.getInputTitle().val(note.getHeaderTitle().text());
    toggleNoteTitleInput(note);
  });
}

function processNoteTitleChange(note, noteDiv) {
  note.getHeaderTitle().text(note.getInputTitle().val());

  if (note.getHeaderTitle().text().length > 20 || isEmpty(note.getHeaderTitle().text())) {
    noteDiv.find('div input').addClass('is-invalid');
    return false;
  }

  if (note.getPreviousTitle() !== note.getHeaderTitle().text()) {
    note.setPreviousTitle(note.getHeaderTitle().text());
  }

  // Sending the new title off to the server.
  $.post("backend/ChangeNoteTitle.php", {
    projectName: $('title').text(),
    noteId: noteDiv.attr('id').split('-')[1],
    title: note.getHeaderTitle().text()
  });

  note.setIsInputTitle(false);
  return true;
}

function toggleNoteTitleInput(note) {
  note.getHeaderTitle().toggle();
  note.getInputTitle().toggle();
  note.getInputTitle().focus();
}

function findCurrentId() {
  var maxId = -1;
  $('.notes-style').each(function() {
    maxId = parseInt($(this).attr('id').split('-')[1]);
  });
  currentNoteId = maxId + 1;
}
