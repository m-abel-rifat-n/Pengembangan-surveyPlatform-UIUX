import React, { useEffect } from 'react';
import { Fullscreen } from 'react-bootstrap-icons';

function EmbedDesign({ surveys }) {
    useEffect(() => {
        const designContainer = document.getElementById(
            "embed-design-container"
        );
        const prototypeContainer = document.getElementById(
            "embed-prototype-container"
        );
        const websiteContainer = document.getElementById(
            "embed-website-container"
        );

        if (surveys.embed_design) {
            if (surveys.embed_design.includes("<iframe")) {
                designContainer.innerHTML = surveys.embed_design;
            } else {
                const figmaEmbed = document.createElement("iframe");
                figmaEmbed.setAttribute("width", "90%");
                figmaEmbed.setAttribute("height", "500");
                figmaEmbed.setAttribute("frameborder", "0");
                figmaEmbed.setAttribute("allowfullscreen", true);
                figmaEmbed.setAttribute("src", surveys.embed_design);

                designContainer.appendChild(figmaEmbed);
            }
        }

        if (surveys.embed_prototype) {
            if (surveys.embed_prototype.includes("<iframe")) {
                prototypeContainer.innerHTML = surveys.embed_prototype;
            } else {
                const figmaEmbed = document.createElement("iframe");
                figmaEmbed.setAttribute("width", "90%");
                figmaEmbed.setAttribute("height", "720");
                figmaEmbed.setAttribute("frameborder", "0");
                figmaEmbed.setAttribute("allowfullscreen", true);
                figmaEmbed.setAttribute("src", surveys.embed_prototype);

                prototypeContainer.appendChild(figmaEmbed);
            }
        }

        if (surveys.url_website) {
            if (surveys.url_website.includes("<iframe")) {
                websiteContainer.innerHTML = surveys.url_website;
            } else {
                const websiteEmbed = document.createElement("iframe");
                websiteEmbed.setAttribute("width", "90%");
                websiteEmbed.setAttribute("height", "500");
                websiteEmbed.setAttribute("frameborder", "0");
                websiteEmbed.setAttribute("allowfullscreen", true);
                websiteEmbed.setAttribute("src", surveys.url_website);

                websiteContainer.appendChild(websiteEmbed);
            }
        }
    }, [surveys]);

    return (
        <div className="content-center align-items-center">
            <div>
                {surveys.embed_design && (
                    <div
                        id="embed-design-container"
                        className="embed-responsive mb-2"
                    ></div>
                )}
                {surveys.embed_prototype && (
                    <div
                        id="embed-prototype-container"
                        className="embed-responsive mb-2"
                    ></div>
                )}
                {surveys.url_website && (
                    <div
                        id="embed-website-container"
                        className="embed-responsive mb-2"
                    ></div>
                )}
            </div>
        </div>
    );
}

export default EmbedDesign;
