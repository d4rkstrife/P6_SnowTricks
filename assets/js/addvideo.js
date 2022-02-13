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
};



const collectionHelper = () => {
    console.log('tyty');
    const button = document.querySelector('.add_item_link');
    //  
    console.log('toto', button, 'yuyu');
    button.addEventListener("click", addFormToCollection);
}
document.addEventListener('DOMContentLoaded', function() { collectionHelper() });