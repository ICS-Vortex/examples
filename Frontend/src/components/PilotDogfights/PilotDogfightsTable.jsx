import React from 'react';
import {NumberParam, useQueryParam} from 'use-query-params';

const PilotDogfightsTable = (props) => {
    const [serverId] = useQueryParam('server', NumberParam);
    const [tourId] = useQueryParam('tour', NumberParam);

    const options = {
        filterType: 'checkbox',
        filter: false
    };
    const columns = [
        {
            name: "Time", field: "time", options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    return dogfight.killTime;
                }
            },
        },
        {
            name: "Winner", field: "pilotCallsign", filter: false, options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    return <a href={`/pilot/${dogfight.pilotId}?server=${serverId}&tour=${tourId}`}
                              className={`text-${dogfight.pilotSide.toLowerCase()}`}>{dogfight.pilotCallsign}</a>;
                }
            },
        },
        {
            name: "Plane", field: "pilotPlane", options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    const url = `${process.env.REACT_APP_API_HOST}/images/planes/${dogfight.pilotPlane.toLowerCase()}.png`;
                    return <div title={dogfight.pilotPlane}>
                        <img width={50} src={url} alt={dogfight.pilotPlane}/>
                    </div>;
                }
            },
        },
        {
            name: "Loser", field: "victimCallsign", filter: false, options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    return <a href={`/pilot/${dogfight.victimId}?server=${serverId}&tour=${tourId}`}
                              className={`text-${dogfight.victimSide.toLowerCase()}`}>{dogfight.victimCallsign}</a>;
                }
            },
        },
        {
            name: "Plane", field: "victimPlane", options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    const url = `${process.env.REACT_APP_API_HOST}/images/planes/${dogfight.victimPlane.toLowerCase()}.png`;
                    return <div title={dogfight.victimPlane}>
                        <img width={50} src={url} alt={dogfight.victimPlane}/>
                    </div>;
                }
            },
        },
    ];
    return <>
        {/*<MaterialDatatable*/}
        {/*    title={'Dogfights'}*/}
        {/*    data={props.dogfights}*/}
        {/*    columns={columns}*/}
        {/*    options={options}*/}
        {/*/>*/}
    </>
};

export default PilotDogfightsTable;
