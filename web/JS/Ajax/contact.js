var count = 0;

function arthur() {
    if(count == 0 || count == 4) {
       count++;
    } else {
        count = 0;
    }
}

function cyrille() {
    if(count == 2 || count == 3) {
        count++;
    } else {
        count = 0;
    }
}

function cedric() {
    if(count == 5) {
        let audio = new Audio("meme/meme.mp3");
        audio.play();
        meme = document.getElementsByClassName('meme');
        memeOn = document.getElementsByClassName('memeOn');
        memeOn[0].style.display = "block";
        for(let i = 0; i < meme.length; i++) {
            meme[i].style.display = "none";
        }
    }
    if(count == 1) {
        count++;
    } else {
        count = 0;
    }
}