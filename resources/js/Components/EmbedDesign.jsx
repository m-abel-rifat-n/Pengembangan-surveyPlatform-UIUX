import React, { useEffect, useRef } from 'react';
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

const SOURCE_FIELD = {
    prototype: "embed_prototype",
    design: "embed_design",
    website: "url_website",
};

function EmbedDesign({ surveys, type = "design" }) {
    const containerRef = useRef(null);
    const source = surveys[SOURCE_FIELD[type]];

    useEffect(() => {
        const container = containerRef.current;
        if (!container || !source) return;

        container.innerHTML = "";

        if (source.includes("<iframe")) {
            container.innerHTML = source;
        } else {
            const iframe = document.createElement("iframe");
            iframe.setAttribute("width", "90%");
            iframe.setAttribute("height", type === "prototype" ? "720" : "500");
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute("allowfullscreen", true);
            iframe.setAttribute(
                "src",
                type === "website" ? source : toEmbedUrl(source)
            );
            container.appendChild(iframe);
        }
    }, [source, type]);

    const handleFullscreen = () => {
        const iframe = containerRef.current?.querySelector("iframe");
        if (iframe) {
            (
                iframe.requestFullscreen ||
                iframe.webkitRequestFullscreen ||
                iframe.mozRequestFullScreen
            )?.call(iframe);
        }
    };

    if (!source) return null;

    return (
        <div className="content-center align-items-center">
            <div ref={containerRef} className="embed-responsive mb-2"></div>
            {type === "website" && (
                <div className="d-flex justify-content-end gap-2 mt-1">
                    <button
                        type="button"
                        onClick={handleFullscreen}
                        className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                        style={{ fontSize: "0.8rem" }}
                    >
                        <Fullscreen size={13} />
                        Fullscreen
                    </button>
                    <a
                        href={source}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                        style={{ fontSize: "0.8rem" }}
                    >
                        <BoxArrowUpRight size={13} />
                        Buka Website
                    </a>
                </div>
            )}
        </div>
    );
}

export default EmbedDesign;
