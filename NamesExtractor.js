function saver(json, name) {
    let blob = new Blob([json], { type: 'application/json' });
    let url = URL.createObjectURL(blob);

    let anchor = document.createElement("a");
    anchor.href = url;
    anchor.setAttribute("download", name);
    anchor.className = "download-js-link";
    anchor.innerHTML = "downloading...";
    anchor.style.display = "none";
    document.body.appendChild(anchor);

    setTimeout(() => {
        anchor.click();
        document.body.removeChild(anchor);
        setTimeout(() => {
            URL.revokeObjectURL(anchor.href);
        }, 250);
    }, 66);
}

function keyNormalize(key) {
    // https://regex101.com/r/v4IK2t/1
    const regex = /.*\/(.*)$/mi;
    let file_name = key.match(regex)[1];

    return file_name;
}

function valueNormalize(value) {
    let new_value = value.split('');
    let template = {
        '\\': '_',
        '/': '_',
        ':': '-',
        '*': '_',
        '?': '7',
        '"': '\'',
        '<': '{',
        '>': '}',
        '|': ' l ',
    };

    new_value = new_value.map(char => template[char] ? template[char] : char);

    return new_value.join('');
}

// =========================================================

let file_name = 'names.json';
let lessons = document.querySelectorAll('#lessons-list li');

let lessons_name = {};
lessons.forEach(lesson => {
    let key = lesson.querySelector('[itemprop="url"]').href;
    key = keyNormalize(key);

    let value = lesson.querySelector('[itemprop="name"]').textContent;
    value = valueNormalize(value);

    lessons_name[key] = value;
});

lessons_name = JSON.stringify(lessons_name, ' ', 4);

console.log(lessons_name);
saver(lessons_name, file_name);