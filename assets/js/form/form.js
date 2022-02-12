// Функция для добавления обработчиков событий
function addHandler(object, event, handler, useCapture) {
      if (object.addEventListener) {
            object.addEventListener(event, handler, useCapture ? useCapture : false);
      } else if (object.attachEvent) {
            object.attachEvent('on' + event, handler);
      } else alert("Add handler is not supported");
}

// Определяем браузеры
var ua = navigator.userAgent.toLowerCase();
var isIE = (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1 && ua.indexOf("webtv") == -1);
var isGecko = (ua.indexOf("gecko") != -1);

// Добавляем обработчики
if (isIE) addHandler (document, "keydown", hotSave);
else addHandler (document, "keypress", hotSave);

function hotSave(evt) {
      // Получаем объект event
      evt = evt || window.event;
      var key = evt.keyCode || evt.which;
      // Определяем нажатие Ctrl+S
      key = !isGecko ? (key == 83 ? 1 : 0) : (key == 115 ? 1 : 0);
      if (evt.ctrlKey && key) {
            // Блокируем появление диалога о сохранении
            if(evt.preventDefault) evt.preventDefault();
evt.returnValue = false;
            // Запускаем любую функцию, по желанию
            ctrls_function();
            // Возвращаем фокус в окно
            window.focus();
            return false;
      }
}