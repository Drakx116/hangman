document.addEventListener('load', () => {
    console.log('OK');
    const choices = document.getElementsByClassName('.choice-letter');
    console.log(choices);
    for (let i = 0; i < choices.length; i++) {
        const choice = choices[i];
        console.log(choice);

        choice.onclick = e => {
            console.log('OK');
            const letter = e.target.value;
            alert(letter);
        };
    }
});

