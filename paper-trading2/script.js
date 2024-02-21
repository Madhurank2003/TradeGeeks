const welcomeText = "TradeGeeks";
let textIndex = 0;
let typingSpeed = 100; // Typing speed in milliseconds
let erasingSpeed = 80; // Erasing speed in milliseconds
let newTextDelay = 2000; // Delay before typing next text in milliseconds
//let welcomeTextElement = document.getElementById("typingText");

// function type() {
//   if (textIndex < welcomeText.length) {
//     welcomeTextElement.textContent += welcomeText[textIndex];
//     textIndex++;
//     setTimeout(type, typingSpeed);
//   } else {
//     setTimeout(erase, newTextDelay);
//   }
// }

function erase() {
  if (textIndex > 0) {
    welcomeTextElement.textContent = welcomeText.substring(0, textIndex - 1);
    textIndex--;
    setTimeout(erase, erasingSpeed);
  } else {
    textIndex = 0;
    setTimeout(type, newTextDelay);
  }
}

//type();
function toggleDropdown() {
  var dropdownMenu = document.getElementById("dropdownMenu");
  if (dropdownMenu.style.display === "block") {
    dropdownMenu.style.display = "none";
  } else {
    dropdownMenu.style.display = "block";
  }
}