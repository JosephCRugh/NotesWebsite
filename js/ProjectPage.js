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

    noteDiv.draggable({ containment: "#notes-container" });

    var self = this;
    noteDiv.find('div span[name="title-edit"]').click(function() {
        self.performEditNoteTitle(self);
    });

    this.inputTitle.on('keyup', function(event) {
      self.performInputTitleKeyUp(self, event)
    });

    this.noteDiv.find('div span[name="notes-delete"]').click(function() {
      self.popupDeleteOptions(self);
    });
  }

  performInputTitleKeyUp(self, event) {
    if (event.keyCode == 13) {
      self.headerTitle.text(self.inputTitle.val());
      self.toggleTitleInput(self);
    }
  }

  performEditNoteTitle(self) {
    if (self.isInputTitle) {
      self.headerTitle.text(self.inputTitle.val());
    }
    self.isInputTitle = !self.isInputTitle;

    self.inputTitle.val(self.headerTitle.text());
    self.toggleTitleInput(self);
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
      new Note(currentNoteId, $(this), $(this).find('div input'), $(this).find('div h3'));
      $(this).attr('id', 'note' + currentNoteId);
      currentNoteId++;
  });
}

function addNotes() {

  $('#add-note-button').click(function() {

    var noteDiv = $('<div class="notes-style">' +
      '<div>' +
        '<h3></h4>' +
        '<span class="glyphicon glyphicon-pencil"></span>' +
        '<input type="text"></input>' +
      '</div>' +
      '<textarea class="form-control z-depth-1"></textarea>' +
      '<div class="notes-bottom">' +
        '<span name="notes-delete" class="glyphicon glyphicon-trash"></span>' +
      '</div>' +
    '</div>');

    $('#notes-container').append(noteDiv);
    let note = new Note(currentNoteId, noteDiv, noteDiv.find('div input'), noteDiv.find('div h3'));
    note.performEditNoteTitle(note);
    noteDiv.attr('id', 'note' + currentNoteId);
    currentNoteId++;
  });
}

function deleteNotes() {

  $('#delete-option').hide();

  $('#delete-option button[name="no-delete"]').click(function() {
    $('#delete-option').hide();
    $('.gray-overlay').hide();
  });

  $('#delete-option button[name="do-delete"]').click(function() {
    $('#notes-container').find('#note' + noteIdToDelete).remove();
    $('#delete-option').hide();
    $('.gray-overlay').hide();
  });
}
