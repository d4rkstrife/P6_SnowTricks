const showElt = () => {
    document.querySelector('.see-media-button').style.display = "none";
    const trickElt = document.querySelector('.trick-page-mid');
    const picturesElt = document.querySelector('.pictures_container');

    if (trickElt !== null) {
        trickElt.style.display = "flex";
    } else if (picturesElt !== null) {
        picturesElt.style.display = "flex";
    }
    console.log(trickElt, picturesElt);
}
const getButton = () => {
    const buttonShow = document.querySelector('.see-media-button');
    buttonShow.addEventListener('click', showElt);

}

document.addEventListener('DOMContentLoaded', function() { getButton() });