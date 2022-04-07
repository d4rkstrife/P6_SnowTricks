const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
    addTagFormDeleteLink(item);
};
const addTagFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer cette vidéo';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}



const collectionHelper = () => {
    document
        .querySelectorAll('ul.tags li')
        .forEach((tag) => {
            addTagFormDeleteLink(tag)
        })
    const button = document.querySelector('.add_item_link');

    button.addEventListener("click", addFormToCollection);
}
document.addEventListener('DOMContentLoaded', function() { collectionHelper() });