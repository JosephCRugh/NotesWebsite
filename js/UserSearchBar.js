function userSearch() {

  $('#form-search-users').on("input", processUserSearch);

  $('#form-search-users').on("keydown", function(event) {
    const key = event.key;
    if ((key === "Backspace" || key === "Delete") && event.repeat) {
        processUserSearch();
    }
  });
}

function processUserSearch() {

  var currentUserSearch = $('#form-search-users').val().split(" ");
  var firstName = "";
  var lastName = "";

  if (currentUserSearch.length > 2) {
    // TODO: display an error to the user.
    $('#form-search-users').addClass('is-invalid');
    hideUserSearchResults();
    return;
  } else if (currentUserSearch.length == 2) {
    $('#form-search-users').removeClass('is-invalid');
    firstName = currentUserSearch[0];
    lastName = currentUserSearch[1];
  } else {
    $('#form-search-users').removeClass('is-invalid');
    firstName = currentUserSearch[0];
  }

  if (isEmpty(firstName)) {
    hideUserSearchResults();
    return;
  }

  var limitedIds = [];
  $('#form-added-users ul li').each(function(index) {
      var userId = $(this).attr('id').split('-')[2];
      limitedIds.push(userId);
  });

  $.post("backend/SearchUserMatch.php", {
    firstName: firstName,
    lastName: lastName,
    limitedIds: limitedIds
  }, function(response) {

    var namesList = $('#form-users-name-displays ul');
    namesList.empty();

    if (isEmpty(response)) {
      $('#form-users-name-displays').hide();
      return;
    }

    $('#form-users-name-displays').show();
    var names = response.split(" ");
    var appendedOnce = false;
    for (var i = 0; i < names.length - 1; i++) {
      var userInfo = JSON.parse(names[i]);

      appendedOnce = true;

      var conjoinedName = userInfo.first_name + " " + userInfo.last_name;
      namesList.append('<li name="' + conjoinedName +  '" id="user-id-' + userInfo.id + '"><label>' + conjoinedName + '</label></li>')
    }

    if (!appendedOnce) {
      $('#form-users-name-displays').hide();
    }

  });
}

function hideUserSearchResults() {
  $('#form-users-name-displays').hide();
  $('#form-users-name-displays ul').empty();
}

function clickedRemoveUser(callback) {
  $('#form-added-users').on('click', 'li span', function() {
    var userId = $(this).parent().attr('id');
    $(this).parent().remove();
    callback(userId.split("-")[2]);
  });
}

function clickedAddUser(callback) {

  $('#form-names-ul-lists').on('click', 'li', function() {
    var fullName = $(this).attr('name');
    var userId = $(this).attr('id');

    hideUserSearchResults();
    $('#form-search-users').val('');

    var glyphRemove = '<span class="glyphicon glyphicon-remove"></span>';
    $('#form-added-users ul').append('<li id="' + userId + '"><label>' + fullName + '</label> ' + glyphRemove + '</li>');

    callback(userId.split("-")[2]);
  });
}
