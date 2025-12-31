import React, { useState } from "react";
import Quill from "quill";
import QuillEditor from "react-quill";
// import BlotFormatter from "quill-blot-formatter";
import "react-quill/dist/quill.snow.css";

// Quill.register("modules/blotFormatter", BlotFormatter);

function Editor({ value, onChange, label, error, mustFill }) {
    const modules = {
        // blotFormatter: {
        //     embed: true,
        // },
        toolbar: [
            [{ font: [] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            ["bold", "italic", "underline", "strike"],
            [
                { list: "ordered" },
                { list: "bullet" },
                { indent: "-1" },
                { indent: "+1" },
            ],
            [{ script: "sub" }, { script: "super" }, "blockquote", "formula"],
            [{ align: [] }, { color: [] }, { background: [] }],
            ["link", "image"],
        ],
        clipboard: {
            matchVisual: false,
        },
    };
    const formats = [
        "font",
        "header",
        "bold",
        "italic",
        "underline",
        "list",
        "strike",
        "ordered",
        "bullet",
        "indent",
        "script",
        "blockquote",
        "formula",
        "align",
        "color",
        "background",
        "link",
        "image",
    ];

    return (
        <div className="mb-3">
            <label className="form-label fw-bold">
                {label}
                {mustFill && <span className="text-danger">*</span>}
            </label>
            <QuillEditor
                theme="snow"
                modules={modules}
                formats={formats}
                value={value}
                onChange={onChange}
            />
            {error && <div className="alert alert-danger">{error}</div>}
        </div>
    );
}

export default Editor;
