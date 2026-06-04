import React, { useEffect } from 'react';
import { Fullscreen, BoxArrowUpRight } from 'react-bootstrap-icons';

const toEmbedUrl = (url) => {
    if (url.includes("figma.com") && !url.includes("<iframe")) {
        if (url.includes("figma.com/embed")) return url;

        if (url.includes("figma.com/proto")) {
            // Prototype: wrap in embed format to bypass X-Frame-Options,
            // but keep all params (node-id, starting-point-node-id) intact
            return `https://www.figma.com/embed?embed_host=share&url=${encodeURIComponent(url)}`;
        }

        // Design/file views: strip node-id to show full canvas
        const cleanUrl = new URL(url);
        cleanUrl.searchParams.delete("node-id");
        cleanUrl.searchParams.delete("node_id");
        return `https://www.figma.com/embed?embed_host=share&url=${encodeURIComponent(cleanUrl.toString())}`;
    }
    return url;
};

const handleWebsiteFullscreen = () => {
    const container = document.getElementById('embed-website-container');
    const iframe = container?.querySelector('iframe');
    if (iframe) {
        (iframe.requestFullscreen || iframe.webkitRequestFullscreen || iframe.mozRequestFullScreen)?.call(iframe);
    }
};

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
                figmaEmbed.setAttribute("src", toEmbedUrl(surveys.embed_design));

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
                figmaEmbed.setAttribute("src", toEmbedUrl(surveys.embed_prototype));

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
                    <div className="mb-2">
                        <div
                            id="embed-website-container"
                            className="embed-responsive"
                        ></div>
                        <div className="d-flex justify-content-end gap-2 mt-1">
                            <button
                                type="button"
                                onClick={handleWebsiteFullscreen}
                                className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                                style={{ fontSize: '0.8rem' }}
                            >
                                <Fullscreen size={13} />
                                Fullscreen
                            </button>
                            <a
                                href={surveys.url_website}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                                style={{ fontSize: '0.8rem' }}
                            >
                                <BoxArrowUpRight size={13} />
                                Buka Website
                            </a>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

export default EmbedDesign;
