function updateOrderInput() {
    let order = '';
    $('#selected_elements > .draggable').each(function () {
        const elementId = $(this).attr('id').replace('_div', '');
        order += elementId + ',';
    });
    order = order.slice(0, -1);
    $('input[name="order"]').val(order);
}

const elementNames = {
    'include-source': 'Source',
    'include-title': 'Title',
    'include-description': 'Description',
    'include-category': 'Category',
    'include-author': 'Author',
    'include-pubDate': 'Publication date',
};

const defaultOrder = ['include-title', 'include-description', 'include-pubDate', 'include-author', 'include-category', 'include-source'];

function updateOrderArray() {
    const orderString = $('#selected_elements').data('order');
    const orderArray = orderString === '' ? defaultOrder.slice() : orderString.split(',');

    // Parcourir les éléments cochés et les ajouter à orderArray s'ils n'y sont pas déjà
    $("input[type=checkbox]:checked").each(function () {
        const elementId = $(this).attr('id');
        if (orderArray.indexOf(elementId) === -1) {
            orderArray.push(elementId);
        }
    });

    // Mettre à jour l'attribut data-order avec la nouvelle valeur de orderArray
    $('#selected_elements').data('data-order', orderArray.join(','));

    return orderArray;
}

function updateSelectedElements() {
    $("#selected_elements").empty().toggle($("input[type=checkbox]:checked").length > 0);

    // Appelez updateOrderArray pour mettre à jour orderArray et data-order
    const orderArray = updateOrderArray();

    // Parcourez le tableau d'ordre et ajoutez les éléments correspondants à la div selected_elements
    orderArray.forEach(function (elementId) {
        const checkbox = $('#' + elementId);
        if (checkbox.is(':checked')) {
            const displayName = elementNames[elementId];
            const elementDiv = $('<div>').addClass('draggable').attr('id', `${elementId}_div`).text(displayName);
            const buttonDiv = $('<div>');
            const upArrow = $('<button>').addClass('arrow').text('▲').attr('type', 'button');
            const downArrow = $('<button>').addClass('arrow').text('▼').attr('type', 'button');
            buttonDiv.append(upArrow);
            buttonDiv.append(downArrow);
            elementDiv.append(buttonDiv);
            $('#selected_elements').append(elementDiv);
        }
    });
    updateOrderInput();
}

$(document).ready(function () {
    $("#selected_elements").hide();

    $("input[type=checkbox]").change(function () {
        updateSelectedElements();
    });

    $(document).on('click', '.arrow', function (e) {
        e.preventDefault();
        const elementDiv = $(this).closest('.draggable');
        if ($(this).text() === '▲') {
            elementDiv.insertBefore(elementDiv.prev());
        } else if ($(this).text() === '▼') {
            elementDiv.insertAfter(elementDiv.next());
        }
        updateOrderInput();
    });

    // Ajoutez cette ligne pour appeler updateSelectedElements() au chargement de la page
    updateSelectedElements();
});

function updateSliderValue(sliderId, displayId, hiddenId) {
    const slider = document.getElementById(sliderId);
    const sliderValue = document.getElementById(displayId);
    const hiddenFontSize = document.getElementById(hiddenId);

    sliderValue.value = slider.value;
    hiddenFontSize.value = slider.value;
}

async function fetchRSSInfoFixed() {
    const rssLink = document.getElementById('fixed_url').value;
    const infoContainer = document.getElementById('rss-info-container');
    if (!rssLink) {
        infoContainer.innerHTML = '<p style="color: red;">Please enter an RSS link</p>';
        return;
    }

    try {
        const response = await fetch('rss_proxy.php?rssLink=' + encodeURIComponent(rssLink));
        const xmlText = await response.text();
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlText, "application/xml");

        // Affichez les informations du flux RSS sur la page
        displayRSSInfo(xmlDoc);
    } catch (error) {
        console.error('Error retrieving information from RSS feed:', error);
    }
}

function displayRSSInfo(xmlDoc) {
    // Supprimez les anciennes informations affichées
    const infoContainer = document.getElementById('rss-info-container');
    while (infoContainer.firstChild) {
        infoContainer.removeChild(infoContainer.firstChild);
    }

    // Créez et ajoutez des éléments HTML pour afficher les noms des balises XML
    const items = xmlDoc.getElementsByTagName("item");
    if (items.length > 0) {
        const firstItem = items[0];
        const tagNames = new Set();

        for (let i = 0; i < firstItem.childNodes.length; i++) {
            const childNode = firstItem.childNodes[i];
            if (childNode.nodeType === Node.ELEMENT_NODE) {
                tagNames.add(childNode.tagName);
            }
        }
        infoContainer.innerHTML = "<p>Here are the different elements that make up the RSS feed that you can use for the GIF:</p>";
        tagNames.forEach(tagName => {
            const tagNameElement = document.createElement("li");
            tagNameElement.textContent = tagName;
            infoContainer.appendChild(tagNameElement);
        });
    } else {
        infoContainer.innerHTML = '<p style="color: red;"">No XML tags found</p>';
    }
}
