import React from "react";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";
import { Pie } from "react-chartjs-2";

ChartJS.register(ArcElement, Tooltip, Legend);

const PieChart = ({ data }) => {
    const calculatePercentage = (value, total) => {
        return ((value / total) * 100).toFixed(2) + "%";
    };

    const generateBackgroundColor = () => {
        const defaultColors = [
            "#FFA600",
            "#FF6361",
            "#BC5090",
            "#58508D",
            "#003F5C",
            "#FF7D00",
            "#FF8E83",
            "#D74177",
            "#7A5B95",
            "#3F2B5B",
            "#FFC300",
            "#FFD166",
            "#FF851B",
            "#F012BE",
            "#B10DC9",
            "#4D4D4D",
            "#AAAAAA",
            "#2ECC40",
            "#01FF70",
            "#2E3192",
            "#FFDC00",
            "#FF4136",
            "#0074D9",
            "#7FDBFF",
            "#85144B",
            "#111111",
        ];

        if (!data.datasets || data.datasets.length === 0) {
            return defaultColors;
        }

        const backgroundColors = data.datasets[0].backgroundColor;
        if (!backgroundColors || backgroundColors.length === 0) {
            return defaultColors;
        }

        return backgroundColors;
    };

    const backgroundColors = generateBackgroundColor();

    const chartData = {
        labels: data.labels,
        datasets: [
            {
                ...data.datasets[0],
                backgroundColor: backgroundColors,
            },
        ],
    };

    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: (context) => {
                        const dataset = data.datasets[context.datasetIndex];
                        const total = dataset.data.reduce(
                            (acc, value) => acc + value,
                            0
                        );
                        const value = dataset.data[context.dataIndex];
                        const percentage = calculatePercentage(value, total);
                        return `${
                            data.labels[context.dataIndex]
                        }: ${value} (${percentage})`;
                    },
                },
            },
            legend: {
                display: true,
                position: "right",
                labels: {
                    usePointStyle: true,
                    generateLabels: function (chart) {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const dataset = data.datasets[0];
                                const value = dataset.data[i];
                                const total = dataset.data.reduce(
                                    (acc, value) => acc + value,
                                    0
                                );
                                const percentage =
                                    ((value / total) * 100).toFixed(2) + "%";

                                return {
                                    text: `${label} (${percentage})`,
                                    fillStyle: dataset.backgroundColor[i],
                                    strokeStyle: "white",
                                    hidden: false,
                                    index: i,
                                };
                            });
                        }
                        return [];
                    },
                },
            },
        },
    };

    return (
        <div className="chart-container">
            {data.labels && data.datasets && (
                <Pie data={chartData} options={options} />
            )}
        </div>
    );
};

export default PieChart;
