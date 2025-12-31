import React, { useState, useEffect } from "react";

export default function InputField({
    label,
    type,
    value,
    onChange,
    placeholder,
    error,
    mustFill,
    disabled,
}) {
    const [previewImage, setPreviewImage] = useState(value);
    useEffect(() => {
        setPreviewImage(value);
    }, [value]);

    const sanitizedValue = value || "";

    const handleFileChange = (e) => {
        onChange(e);
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                setPreviewImage(event.target.result);
            };
            reader.readAsDataURL(file);
        }
    };

    return (
        <div className="mb-3">
            {label && (
                <label className="form-label fw-bold">
                    {label}
                    {mustFill && <span className="text-danger">*</span>}
                </label>
            )}{" "}
            {type === "textarea" ? (
                <textarea
                    className="form-control"
                    value={sanitizedValue}
                    onChange={onChange}
                    placeholder={placeholder}
                />
            ) : type === "file" ? (
                <>
                    <input
                        type="file"
                        className="form-control mb-2"
                        onChange={handleFileChange}
                        accept="image/*"
                    />
                    <div>
                        {previewImage && (
                            <div>
                                <img
                                    src={previewImage}
                                    alt="Preview"
                                    style={{
                                        maxWidth: "100%",
                                        maxHeight: "200px",
                                    }}
                                />
                            </div>
                        )}
                    </div>
                </>
            ) : (
                <input
                    type={type}
                    className="form-control"
                    value={sanitizedValue}
                    onChange={onChange}
                    placeholder={placeholder}
                    disabled={disabled}
                />
            )}
            {error && <div className="alert alert-danger">{error}</div>}
        </div>
    );
}
