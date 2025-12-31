import React from "react";

const TAMTable = ({ data, type }) => {
    return (
        <div className="table-responsive">
            {type === "responsesTable" && renderResponseTable(data)}
            {type === "descriptiveStatisticsTable" &&
                renderDescriptiveStatisticsTable(data)}
            {type === "regressionStatisticsTable" &&
                renderRegressionTable(data)}
        </div>
    );
};

const renderResponseTable = (data) => {
    const keys = Object.keys(data[0]?.answerData);

    return (
        <div className="table-responsive" style={{ overflowY: "auto" }}>
            <table className="table table-striped table-bordered">
                <thead className="thead">
                    <tr>
                        <th>No</th>
                        <th>Respondent Name</th>
                        {keys.map((key) => (
                            <th key={key}>Question {key.replace("tam", "")}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {data.map((result, index) => (
                        <tr key={result.id}>
                            <td>{index + 1}</td>
                            <td>{result.respondentName}</td>
                            {keys.map((key) => (
                                <td key={key}>{result.answerData[key]}</td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

const renderDescriptiveStatisticsTable = (data) => {
    return (
        <table className="table table-striped table-bordered">
            <thead className="thead">
                <tr>
                    <th>Variabel</th>
                    <th>nI (Jumlah Pertanyaan)</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>∑SH (Nilai Yang Didapat)</th>
                    <th>∑SK (Nilai Maksimum)</th>
                    <th>P (Jumlah SH / Jumlah SK)</th>
                </tr>
            </thead>
            <tbody>
                {data.map((result, index) => (
                    <tr key={index}>
                        <td>{result["variable"]}</td>
                        <td>{result["nI"]}</td>
                        <td>{result["min"]}</td>
                        <td>{result["max"]}</td>
                        <td>{result["sum_SH"]}</td>
                        <td>{result["sum_SK"]}</td>
                        <td>{result["P"]}</td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
};

const renderRegressionTable = (data) => {
    return (
        <div className="table-responsive">
            <table className="table table-striped table-bordered">
                <thead className="thead">
                    <tr>
                        <th>Variable Regresi</th>
                        <th>n</th>
                        <th>𝛴x</th>
                        <th>𝛴y</th>
                        <th>𝛴xy</th>
                        <th>𝛴x^2</th>
                        <th>(𝛴x)^2</th>
                        {/* <th>Konstanta</th> */}
                        <th>Koefisien Regresi (Slope)</th>
                    </tr>
                </thead>
                <tbody>
                    {data.map((result, index) => (
                        <tr key={index}>
                            <td>{result["path"]}</td>
                            <td>{result["n"]}</td>
                            <td>{result["x_sum"]}</td>
                            <td>{result["y_sum"]}</td>
                            <td>{result["xy_sum"]}</td>
                            <td>{result["x_squared_sum"]}</td>
                            <td>{result["x_sum_squared"]}</td>
                            {/* <td>{result["a"]}</td> */}
                            <td>{result["b"]}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default TAMTable;
