
      // Get all the increment and decrement buttons
      var incrementButtons = document.querySelectorAll('.increment');
      var decrementButtons = document.querySelectorAll('.decrement');

      // Add event listeners to handle button clicks
      incrementButtons.forEach(function (button) {
          button.addEventListener('click', function () {
              var input = button.parentNode.querySelector('.quantity');
              //built-in js function to increment quantity
              input.stepUp();
          });
      });

      decrementButtons.forEach(function (button) {
          button.addEventListener('click', function () {
              var input = button.parentNode.querySelector('.quantity');
              if (input.value > 1) {
                  input.stepDown();
              }
          });
      });