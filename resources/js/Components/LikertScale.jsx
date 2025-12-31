import React from "react";

function LikertScale({ name, onValueChange, selectedValue }) {
    const options = [
        { value: 5, label: "Sangat setuju" },
        { value: 4, label: "Setuju" },
        { value: 3, label: "Netral" },
        { value: 2, label: "Tidak setuju" },
        { value: 1, label: "Sangat tidak setuju" },
    ];

    return (
        <div className="">
            {options.map((option) => (
                <div
                    key={`${name}-${option.value}`}
                    className="form-check mb-1"
                >
                    <input
                        type="radio"
                        className="form-check-input"
                        name={name}
                        id={`${name}-${option.value}`}
                        onChange={() => onValueChange(name, option.value)}
                        checked={selectedValue === option.value}
                        value={option.value}
                        required
                    />
                    <label
                        htmlFor={`${name}-${option.value}`}
                        className="form-check-label"
                    >
                        {option.label}
                    </label>
                </div>
            ))}
        </div>
    );
}

export default LikertScale;
