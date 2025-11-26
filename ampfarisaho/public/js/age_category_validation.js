const categoryRanges = {
"Infants": [0.5, 1],
"Toddlers": [1, 3],
"Playgroup": [3, 4],
"Pre-School": [4, 5]
};


function validateCategory() {
const dob = new Date(document.getElementById('child_dob').value);
const today = new Date();


if (!dob) return;


let age = (today - dob) / (365 * 24 * 60 * 60 * 1000);


const dropdown = document.getElementById('grade_category');


for (let option of dropdown.options) {
if (categoryRanges[option.value]) {
const [min, max] = categoryRanges[option.value];
option.disabled = age < min || age > max;
}
}
}
