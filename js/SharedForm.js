function resetButtonFail(btn, originalText) {
  $("input").click(function() {
    btn.text(originalText);
    btn.removeClass("btn-danger");
    btn.addClass("btn-primary");
  });
}

function setFailButton(btn, msg) {
  btn.text(msg);
  btn.removeClass("btn-primary");
  btn.addClass("btn-danger");
}

function clearInvInput(input) {
  input.click(function() {
    input.removeClass('is-invalid');
  });
}

function isEmpty(str) {
  return !str || str.length === 0;
}
