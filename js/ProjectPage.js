// ---------------------------------------------
// Notes structure
// ---------------------------------------------

var noteIdToDelete = -1;
var notes = new Map();
var editingNote = false;

class Note {

  constructor(noteId, noteDiv, inputTitle, headerTitle) {
    this.noteId = noteId;
    this.isInputTitle = false;
    this.inputTitle = inputTitle;
    this.headerTitle = headerTitle;
    this.noteDiv = noteDiv;
    this.previousTitle = headerTitle.text();
    this.waitingOnResponse = false;
    this.isLocked = false;
    this.textAreaGainedFocus = false;
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

  setIsWaitingOnResponse(tof) {
    this.waitingOnResponse = tof;
  }

  getIsWaitingOnResponse() {
    return this.waitingOnResponse;
  }

  setIsLocked(tof) {
    this.isLocked = tof;
  }

  getIsLocked() {
    return this.isLocked;
  }

  setTextAreaGainedFocus(tof) {
    this.textAreaGainedFocus = tof;
  }

  getTextAreaGainedFocus() {
    return this.textAreaGainedFocus;
  }
}

// ---------------------------------------------
// Websocket structure
// ---------------------------------------------

var socket;

function init() {

  socket = new WebSocket('ws://' + $('#sock-address').attr('value') + ':8080');
  socket.addEventListener('message', onMessageReceived);

}

function onMessageReceived(event) {
  var response = JSON.parse(event.data);

  if (response.type == "response") {
    handleLockResponse(response);
  } else if (response.type == "clientlocked") {
    handleOtherClientLocking(response);
  } else if (response.type == "clientunlocked") {
    handleOtherClientunLocking(response);
  } else if (response.type == "addnote") {
    addNewNote(response.noteId);
  } else if (response.type == "deletenote") {
    handleNoteDeletion(response);
  }

}

function handleLockResponse(response) {

  var note = notes.get(parseInt(response.noteId));
  note.setIsWaitingOnResponse(false);

  if (!note) {
    return;
  }

  // We can now edit since the lock is free
  if (response.state == "success") {

    if (response.editing == "title") {
      note.setIsInputTitle(true);
      note.getInputTitle().val(note.getHeaderTitle().text());
      toggleNoteTitleInput(note);
    } else if (response.editing == "content") {
      var noteDiv = note.getNoteDiv();
      noteDiv.find('div button').removeAttr('hidden');
      noteDiv.find('div button').show();
      noteDiv.find('.notes-bottom').css('height', '42px');
      noteDiv.find('textarea').prop('readonly', false);
      note.setTextAreaGainedFocus(true);
    }

    editingNote = true;

  }
}

function handleOtherClientLocking(response) {
  var note = notes.get(parseInt(response.noteId));
  if (note) {

    note.setIsLocked(true);

  }
}

function handleOtherClientunLocking(response) {
  var note = notes.get(parseInt(response.noteId));
  if (note) {

    if (response.editing == "title") {
      note.getHeaderTitle().text(response.editval);
    } else if (response.editing == "content") {
      note.getNoteDiv().find('textarea').val(response.editval);
    } else if (response.editing == "position") {
      var noteDiv = note.getNoteDiv();
      var position = response.editval.split(":");
      noteDiv.css('left', position[0]);
      noteDiv.css('top', position[1]);
    }

    note.setIsLocked(false);

  }
}

function handleNoteDeletion(response) {
  $('#notes-container').find('#note-' + response.noteId).remove();
  notes.delete(response.noteId);
}

window.addEventListener("load", init, false);

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
      let note = new Note(noteId, $(this), $(this).find('div input'), $(this).find('div h3'));
      performNoteActions(note);
      notes.set(noteId, note);
  });
}

function addNotes() {

  $('#add-note-button').click(function() {

    $.post("backend/AddNote.php", {
      projectId: $('body').attr('id').split('-')[2],
      title: "Title",
      posX: 0,
      posY: 82
    }, function(response) {

      var note = addNewNote(parseInt(response));
      var noteDiv = note.getNoteDiv();

      socket.send(JSON.stringify({
        "type": "addnote",
        noteId: note.getNoteId(),
      }));

      editingNote = false;
    });
  });
}

function addNewNote(setId) {
  var noteDiv = $('<div class="notes-style">' +
    '<div>' +
      '<h3>Title</h3>' +
      '<span name="title-edit" class="glyphicon glyphicon-pencil"></span>' +
      '<input type="text" class="form-control"></input>' +
    '</div>' +
    '<textarea class="form-control z-depth-1"></textarea>' +
    '<div class="notes-bottom">' +
      '<button type="text" class="btn btn-primary" style="width: 100%;" hidden>Finish Edit</button>' +
      '<span name="notes-delete" class="glyphicon glyphicon-trash"></span>' +
    '</div>' +
  '</div>');

  $('#notes-container').append(noteDiv);
  let note = new Note(setId, noteDiv, noteDiv.find('div input'), noteDiv.find('div h3'));
  noteDiv.attr('id', 'note-' + setId);
  notes.set(setId, note);
  noteDiv.find('div input').hide();

  performNoteActions(note);

  return note;
}

function deleteNotes() {

  $('#delete-option').hide();

  $('#delete-option button[name="no-delete"]').click(function() {
    $('#delete-option').hide();
    $('.gray-overlay').hide();
  });

  $('#delete-option button[name="do-delete"]').click(function() {
    $('#delete-option').hide();
    $('.gray-overlay').hide();

    var note = notes.get(noteIdToDelete);
    requestNoteLock(note, "na");

    // Telling the server to delete the note.
    $.post("backend/DeleteNote.php", {
      projectId: $('body').attr('id').split('-')[2],
      noteId: noteIdToDelete
    }, function(response) {

      socket.send(JSON.stringify({
        "type": "deletenote",
        noteId: note.getNoteId()
      }));

      $('#notes-container').find('#note-' + noteIdToDelete).remove();
      notes.delete(noteIdToDelete);

      editingNote = false;
    });
  });
}

function performNoteActions(note) {

  var noteDiv = note.getNoteDiv();

  processNoteContentEdit(note, noteDiv);

  processNotePopupDltOpts(note, noteDiv);

  performNoteInputTitleKeyUp(note, noteDiv);

  performEditNoteTitle(note, noteDiv);

  performNoteMovement(note, noteDiv);

}

function processNoteContentEdit(note, noteDiv) {

  noteDiv.find('textarea').focusin(function() {
    requestNoteLock(note, "content");
  });

  noteDiv.find('textarea').prop('readonly', true);

  var contentEditCallback = function() {

    if (!note.getTextAreaGainedFocus()) {
      return;
    }

    var content = noteDiv.find('textarea').val();

    if (content.length > 500) {
        noteDiv.find('textarea').focus();
        return;
    }

    noteDiv.find('.notes-bottom').css('height', '20px');
    noteDiv.find('button').hide();

    $.post('backend/ChangeNoteContent.php', {
      projectId: $('body').attr('id').split('-')[2],
      noteId: note.getNoteId(),
      content: content
    }, function(response) {

      socket.send(JSON.stringify({
        "type": "unlock",
        noteId: note.getNoteId(),
        "editing": "content",
        "editval": content
      }));

      noteDiv.find('textarea').prop('readonly', true);
      note.setTextAreaGainedFocus(false);
      editingNote = false;
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
      } else {
        note.setIsInputTitle(false);
        toggleNoteTitleInput(note);
      }
    } else {
      requestNoteLock(note, "title");
    }
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
    projectId: $('body').attr('id').split('-')[2],
    noteId: note.getNoteId(),
    title: note.getHeaderTitle().text()
  }, function(response) {

    socket.send(JSON.stringify({
      "type": "unlock",
      noteId: note.getNoteId(),
      "editing": "title",
      "editval": note.getHeaderTitle().text()
    }));

    editingNote = false;
  });

  return true;
}

function requestNoteLock(note, editing) {
  if (note.getIsWaitingOnResponse() || note.getIsLocked() || editingNote) {
    return;
  }
  socket.send(JSON.stringify({
    "type": "lock",
    noteId: note.getNoteId(),
    "editing": editing
  }));
  note.setIsWaitingOnResponse(true);
}

function performNoteMovement(note, noteDiv) {
  noteDiv.draggable({
    containment: "#notes-container",
    start: function(evnet, ui) {
      requestNoteLock(note, "na");
    },
    stop: function(event, ui) {

      socket.send(JSON.stringify({
        "type": "unlock",
        noteId: note.getNoteId(),
        "editing": "position",
        "editval": $(this).position().left + ":" + $(this).position().top
      }));
      note.setIsWaitingOnResponse(false);
      editingNote = false;

      $.post('backend/ChangeNotePosition.php', {
        projectId: $('body').attr('id').split('-')[2],
        noteId: note.getNoteId(),
        posX: $(this).position().left,
        posY: $(this).position().top
      });
    }
  });
}

function toggleNoteTitleInput(note) {
  note.getHeaderTitle().toggle();
  note.getInputTitle().toggle();
  note.getInputTitle().focus();
}
