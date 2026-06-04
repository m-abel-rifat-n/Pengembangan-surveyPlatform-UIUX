import React, { useState } from "react";

export default function NasaTlxForm({ onValuesChange, initialValues = {} }) {
    const [values, setValues] = useState({
        mental_demand: initialValues.mental_demand || 0,
        physical_demand: initialValues.physical_demand || 0,
        temporal_demand: initialValues.temporal_demand || 0,
        performance: initialValues.performance || 0,
        effort: initialValues.effort || 0,
        frustration: initialValues.frustration || 0,
    });

    const dimensions = [
        {
            key: "mental_demand",
            label: "Mental Demand",
            question: "Seberapa besar aktivitas mental dan persepsi yang dibutuhkan (misalnya: berpikir, memilih, menghitung, mengingat)?",
            invert: false,
            lowLabel: "Rendah (0)",
            highLabel: "Tinggi (100)",
        },
        {
            key: "physical_demand",
            label: "Physical Demand",
            question: "Seberapa besar aktivitas fisik yang dibutuhkan (misalnya: mengklik, mengetik, menggerakkan mouse)?",
            invert: false,
            lowLabel: "Rendah (0)",
            highLabel: "Tinggi (100)",
        },
        {
            key: "temporal_demand",
            label: "Temporal Demand",
            question: "Seberapa besar tekanan waktu yang Anda rasakan karena kecepatan atau ritme tugas/sistem?",
            invert: false,
            lowLabel: "Rendah (0)",
            highLabel: "Tinggi (100)",
        },
        {
            key: "performance",
            label: "Performance",
            question: "Seberapa berhasilkah Anda dalam menyelesaikan apa yang diminta untuk Anda lakukan?",
            invert: false,
            lowLabel: "Sempurna (0)",
            highLabel: "Gagal (100)",
        },
        {
            key: "effort",
            label: "Effort",
            question: "Seberapa keras Anda harus bekerja (secara mental dan fisik) untuk mencapai tingkat keberhasilan Anda?",
            invert: false,
            lowLabel: "Rendah (0)",
            highLabel: "Tinggi (100)",
        },
        {
            key: "frustration",
            label: "Frustration",
            question: "Seberapa besar rasa tidak aman, putus asa, jengkel, dan stres yang Anda rasakan saat menggunakan sistem?",
            invert: false,
            lowLabel: "Rendah (0)",
            highLabel: "Tinggi (100)",
        },
    ];

    const calculateScore = (dimension, inputValue) => {
        const value = parseInt(inputValue);
        return dimension.invert ? 100 - value : value;
    };

    const handleChange = (key, value) => {
        const dimension = dimensions.find(d => d.key === key);
        const actualScore = calculateScore(dimension, value);

        const updatedValues = {
            ...values,
            [key]: actualScore,
        };
        setValues(updatedValues);
        onValuesChange(updatedValues);
    };

    const getDisplayValue = (dimension) => {
        return values[dimension.key];
    };

    return (
        <div className="nasa-tlx-form">
            <div className="alert alert-info mb-4">
                <h5 className="alert-heading">NASA-TLX Assessment</h5>
                <p className="mb-0">
                    Sebelum menjawab pertanyaan di bawah ini, bacalah setiap pertanyaan dengan cermat.
                    Gunakan slider untuk memberikan penilaian Anda dari 0 (rendah) hingga 100 (tinggi) untuk setiap dimensi.
                </p>
                <p className="mb-0 mt-2 fw-semibold">
                Nilai rendah = sistem ringan &amp; optimal. <br />Nilai tinggi = sistem terasa berat &amp; melelahkan.
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
                            <div className="row align-items-center">
                                <div className="col-md-10">
                                    <input
                                        type="range"
                                        min="0"
                                        max="100"
                                        step="5"
                                        value={dimension.invert ? 100 - values[dimension.key] : values[dimension.key]}
                                        onChange={(e) =>
                                            handleChange(dimension.key, e.target.value)
                                        }
                                        className="form-range"
                                    />
                                    <div className="d-flex justify-content-between mt-2 text-sm text-muted">
                                        <small>{dimension.lowLabel}</small>
                                        <small>{dimension.highLabel}</small>
                                    </div>
                                </div>
                                <div className="col-md-2 text-center">
                                    <div
                                        className="badge bg-primary p-2"
                                        style={{ fontSize: "14px" }}
                                    >
                                        {getDisplayValue(dimension)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
