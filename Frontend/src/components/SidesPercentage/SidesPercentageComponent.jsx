import React, {useEffect} from 'react';

const SidesPercentageComponent = ({header, server, tour, type}) => {
    const [blue, setBlue] = React.useState(0);
    const [bluePercent, setBluePercent] = React.useState(0);
    const [red, setRed] = React.useState(0);
    const [redPercent, setRedPercent] = React.useState(0);

    useEffect(() => {
        if (server.id) {
            fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/${type}/${tour}`)
                .then(r => r.json())
                .then(data => {
                    setRedPercent(Math.round(data.RED / data.total * 100));
                    setBluePercent(Math.round(data.BLUE / data.total * 100));
                    setBlue(data.BLUE);
                    setRed(data.RED);
                })
            ;
        }
    }, [server, tour]);

    const getPercent = () => {
        if (red === 0 && blue === 0) {
            return 50;
        }
        if (red !== 0 && blue === 0) {
            return redPercent;
        }
        if (red === 0 && blue !== 0) {
            return 0;
        }
        return redPercent;
    };

    return (
        <div className="stats__block">
            <div className="stats__title">{header}</div>
            <div className="percent">
                <div className="percent__line">
                    <div className="percent__progress" style={{width: `${getPercent()}%`}}/>
                </div>
                <div className="percent__value">
                    <div className="percent__value-axis">{red}</div>
                    <div className="percent__value-allied">{blue}</div>
                </div>
            </div>
        </div>
    );
}

export default SidesPercentageComponent;