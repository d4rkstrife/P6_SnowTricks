const showElt = () => {
    document.querySelector('.see-media-button').style.display = "none";
    document.querySelector('.trick-page-mid').style.display = "flex";
    document.querySelector('.pictures_container').style.display = "flex";
    console.log(elt);
}
const getButton = () => {
    const buttonShow = document.querySelector('.see-media-button');
    buttonShow.addEventListener('click', showElt);

}

document.addEventListener('DOMContentLoaded', function() { getButton() });