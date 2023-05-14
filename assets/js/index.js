document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('generate-gif').addEventListener('click', function(event) {
        event.preventDefault();

        const rssLink = document.getElementById('rss-link');
        const rssLinkValue = rssLink.value.trim();

        // Vérifie si l'URL contient xml, rss, rdf, atom ou feed
        const rssPattern = /(xml|rss|rdf|atom|feed)/;
        if (!rssPattern.test(rssLinkValue)) {
            document.getElementById('error-message').innerHTML = 'Please enter a valid RSS feed URL.';
            return;
        }

        // Soumettre le formulaire si la validation est réussie
        event.target.closest('form').submit();
    });
});

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

$(document).ready(function () {
    $("#selected_elements").hide();

    $("input[type=checkbox]").change(function () {
        $("#selected_elements").empty().toggle($("input[type=checkbox]:checked").length > 0);

        $("input[type=checkbox]").each(function () {
            if (this.checked) {
                const displayName = elementNames[this.id];
                const elementDiv = $('<div>').addClass('draggable').attr('id', `${this.id}_div`).text(displayName);
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
});

function updateSliderValue(sliderId, displayId, hiddenId) {
    const slider = document.getElementById(sliderId);
    const sliderValue = document.getElementById(displayId);
    const hiddenFontSize = document.getElementById(hiddenId);

    sliderValue.value = slider.value;
    hiddenFontSize.value = slider.value;
}

async function fetchRSSInfo() {
    const rssLink = document.getElementById('rss-link').value;
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

