import React from "react";
import parse from "html-react-parser";

function SurveyDescription({ description }) {
    // Gunakan parse() untuk mengurai HTML dari database
    const parsedDescription = parse(description);

    return <div className="survey-description">{parsedDescription}</div>;
}

export default SurveyDescription;
