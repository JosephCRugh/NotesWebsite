var noteIdToDelete = -1;
var currentNoteId = 0;

class Note {

  constructor(noteId, noteDiv, inputTitle, headerTitle) {
    this.noteId = noteId;
    this.isInputTitle = false;
    this.inputTitle = inputTitle;
    this.inputTitle.hide();
    this.headerTitle = headerTitle;
    this.noteDiv = noteDiv;
    this.previousTitle = headerTitle.text();

    noteDiv.draggable({ containment: "#notes-container" });

    var self = this;
    noteDiv.find('div span[name="title-edit"]').click(function() {
        self.performEditNoteTitle(self);
    });

    this.inputTitle.on('keyup', function(event) {
      $(this).removeClass('is-invalid');
      self.performInputTitleKeyUp(self, event)
    });

    this.noteDiv.find('textarea').focusin(function() {
      self.noteDiv.find('div button').removeAttr('hidden');
      self.noteDiv.find('div button').show();
      self.noteDiv.find('.notes-bottom').css('height', '42px');
    });

    this.noteDiv.find('textarea').focusout(function() {
      self.processContentEdit(self);
    });

    this.noteDiv.find('button').click(function() {
      self.processContentEdit(self);
    });

    this.noteDiv.find('div span[name="notes-delete"]').click(function() {
      self.popupDeleteOptions(self);
    });
  }

  processContentEdit(self) {
    var content = self.noteDiv.find('textarea').val();

    if (content.length > 500) {
        self.noteDiv.find('textarea').focus();
        return;
    }

    self.noteDiv.find('.notes-bottom').css('height', '20px');
    self.noteDiv.find('button').hide();

    $.post('backend/ChangeNoteContent.php', {
      projectName: $('title').text(),
      noteId: self.noteId,
      content: content
    });
  }

  performInputTitleKeyUp(self, event) {
    if (event.keyCode == 13) {
      self.isInputTitle = false;
      if (self.processTitleChange(self)) {
        self.toggleTitleInput(self);
      }
    }
  }

  performEditNoteTitle(self) {
    if (self.isInputTitle) {
      if (!self.processTitleChange(self)) {
        return;
      }
    }
    self.isInputTitle = !self.isInputTitle;

    self.inputTitle.val(self.headerTitle.text());
    self.toggleTitleInput(self);
  }

  processTitleChange(self) {
    self.headerTitle.text(self.inputTitle.val());

    if (self.headerTitle.text().length > 20 || isEmpty(self.headerTitle.text())) {
      self.noteDiv.find('div input').addClass('is-invalid');
      return false;
    }

    if (this.previousTitle !== self.headerTitle.text()) {
      this.previousTitle = self.headerTitle.text();
    }

    // Sending the new title off to the server.
    $.post("backend/ChangeNoteTitle.php", {
      projectName: $('title').text(),
      noteId: self.noteDiv.attr('id').split('-')[1],
      title: self.headerTitle.text()
    });

    self.isInputTitle = false;
    return true;
  }

  toggleTitleInput(self) {
    self.headerTitle.toggle();
    self.inputTitle.toggle();
    self.inputTitle.focus();
  }

  popupDeleteOptions(self) {
    $('#delete-option').removeAttr('hidden');
    $('#delete-option').show();
    $('.gray-overlay').removeAttr('hidden');
    $('.gray-overlay').show();
    noteIdToDelete = self.noteId;
  }
}

$(document).ready(function() {

  setupNodes();

  addNotes();

  deleteNotes();

});

function setupNodes() {
  $('.notes-style').each(function (noteDiv) {
      var noteId = parseInt($(this).attr('id').split('-')[1]);
      new Note(noteId, $(this), $(this).find('div input'), $(this).find('div h3'));
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
        '<span name="notes-delete" class="glyphicon glyphicon-trash"></span>' +
      '</div>' +
    '</div>');

    $('#notes-container').append(noteDiv);
    let note = new Note(currentNoteId, noteDiv, noteDiv.find('div input'), noteDiv.find('div h3'));
    noteDiv.attr('id', 'note-' + currentNoteId);

    $.post("backend/AddNote.php", {
      projectName: $('title').text(),
      title: "Title",
      posX: noteDiv.position().left,
      posY: noteDiv.position().top
    });

    note.performEditNoteTitle(note);

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

    console.log("trying to delete a note..");
    // Telling the server to delete the note.
    $.post("backend/DeleteNote.php", {
      projectName: $('title').text(),
      noteId: noteIdToDelete
    });

    findCurrentId();
  });
}

function findCurrentId() {
  var maxId = -1;
  $('.notes-style').each(function() {
    maxId = parseInt($(this).attr('id').split('-')[1]);
  });
  currentNoteId = maxId + 1;
}
