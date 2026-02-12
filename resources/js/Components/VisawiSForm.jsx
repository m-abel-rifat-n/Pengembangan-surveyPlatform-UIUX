import React, { useState } from "react";

export default function VisawiSForm({ onValuesChange, initialValues = {} }) {
    const [values, setValues] = useState({
        simplicity: initialValues.simplicity || "",
        diversity: initialValues.diversity || "",
        colorfulness: initialValues.colorfulness || "",
        craftsmanship: initialValues.craftsmanship || "",
    });

    const dimensions = [
        {
            key: "simplicity",
            label: "Simplicity (Kesederhanaan)",
            question: "Tata letak (layout) sistem ini tampak jelas dan sederhana.",
        },
        {
            key: "diversity",
            label: "Diversity (Keberagaman)",
            question: "Desain visual sistem ini terlihat bervariasi dan menarik.",
        },
        {
            key: "colorfulness",
            label: "Colorfulness (Kekayaan Warna)",
            question: "Komposisi warna yang digunakan pada sistem ini sangat menarik.",
        },
        {
            key: "craftsmanship",
            label: "Craftsmanship (Keahlian)",
            question: "Tata letak sistem ini dirancang dengan sangat profesional.",
        },
    ];

    const likertOptions = [
        { value: 1, label: "Sangat Tidak Setuju" },
        { value: 2, label: "Tidak Setuju" },
        { value: 3, label: "Agak Tidak Setuju" },
        { value: 4, label: "Netral" },
        { value: 5, label: "Agak Setuju" },
        { value: 6, label: "Setuju" },
        { value: 7, label: "Sangat Setuju" },
    ];

    const handleChange = (key, value) => {
        const updatedValues = {
            ...values,
            [key]: value,
        };
        setValues(updatedValues);
        onValuesChange(updatedValues);
    };

    return (
        <div className="visawi-s-form">
            <div className="alert alert-info mb-4">
                <h5 className="alert-heading">VisAWI-S Assessment</h5>
                <p className="mb-0">
                    VisAWI-S adalah versi singkat untuk mengukur estetika visual antarmuka berdasarkan 4 dimensi utama.
                    Silakan jawab setiap pertanyaan dengan memilih tingkat persetujuan Anda menggunakan skala 1-7.
                </p>
            </div>

            <div className="space-y-6">
                {dimensions.map((dimension, index) => (
                    <div key={dimension.key} className="card mb-4">
                        <div className="card-body">
                            <h6 className="card-title mb-2">
                                {index + 1}. {dimension.label}
                            </h6>
                            <p className="card-text text-muted mb-3">
                                {dimension.question}
                            </p>
                            <div className="likert-scale">
                                <div className="row">
                                    {likertOptions.map((option) => (
                                        <div
                                            key={option.value}
                                            className="col-6 col-md-2 mb-2"
                                        >
                                            <label className="form-check">
                                                <input
                                                    type="radio"
                                                    name={dimension.key}
                                                    value={option.value}
                                                    checked={values[dimension.key] == option.value}
                                                    onChange={(e) =>
                                                        handleChange(dimension.key, e.target.value)
                                                    }
                                                    className="form-check-input"
                                                />
                                                <span className="form-check-label d-block text-center">
                                                    <strong>{option.value}</strong>
                                                    <br />
                                                    <small className="text-muted" style={{ fontSize: "10px" }}>
                                                        {option.label.split(" ").slice(0, 2).join(" ")}
                                                    </small>
                                                </span>
                                            </label>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
