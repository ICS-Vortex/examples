import React from 'react';
import './AirStatsComponent.module.css';

const AirStatsComponent = (props) => {
    return (
        <>
            {/*<TableContainer component={Paper}>*/}
            {/*    <Table aria-label="simple table">*/}
            {/*        <TableHead>*/}
            {/*            <TableRow>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Callsign"><Icons.AccountCircle/></span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Flight time"><Icons.Timer/></span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Air wins"><Icons.GpsFixed/></span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Air loses"><Icons.LocalHospital/></span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Takeoffs"><Icons.FlightTakeoff/></span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Landings"><Icons.FlightLand/></span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title="Ranking"><Icons.Star/></span>*/}
            {/*                </TableCell>*/}
            {/*            </TableRow>*/}
            {/*        </TableHead>*/}
            {/*        <TableBody>*/}
            {/*            {props.data.map((row: Air) => (*/}
            {/*                <TableRow key={row.id}>*/}
            {/*                    <TableCell>*/}
            {/*                        {row.country ? <span title={row.country}>*/}
            {/*                        <span className={'mr-5 flag-icon flag-icon-' + row.country.toLowerCase()}/>*/}
            {/*                    </span> : ''}*/}
            {/*                        {row.callsign}*/}
            {/*                    </TableCell>*/}
            {/*                    <TableCell>{row.totalFlightTime}</TableCell>*/}
            {/*                    <TableCell>{row.airWins}</TableCell>*/}
            {/*                    <TableCell>{row.dogfightLoses}</TableCell>*/}
            {/*                    <TableCell>{row.takeoffsCount}</TableCell>*/}
            {/*                    <TableCell>{row.landingsCount}</TableCell>*/}
            {/*                    <TableCell>{Math.round(parseFloat(row.ranking))}</TableCell>*/}
            {/*                </TableRow>*/}
            {/*            ))}*/}
            {/*        </TableBody>*/}
            {/*    </Table>*/}
            {/*</TableContainer>*/}
        </>
    );
}

export default AirStatsComponent;