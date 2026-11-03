@extends('adminlte::page')

@section('title', 'Client Details')

@section('content_header')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Inline CSS Invoice Template</title>
  </head>
  <body>
    <div style="padding: 10px;">
      <table style="width: 100%;">
        <tr style="width: 100%;">
          <td style="width: 50%;">
            <label style="font-size: 40px; font-weight: bold;">INV-001</label>
          </td>
          <td style="width: 50%; text-align: right;">
            <img style="max-width: 200px;" src="DevsDeckLogo.png" />
          </td>
        </tr>
      </table>
      <table style="width: 100%; margin: 10px 0px;">
        <tr style="width: 100%;">
          <td style="width: 33%; line-height: 25px;">
            <label>From</label><br />
            <label style="font-weight: bold; font-size: 20px;"
              >Business Name</label
            >
            <br />
            Address Line 1 <br />
            Address Line 2 <br />
          </td>
          <td style="width: 33%; line-height: 25px;">
            <label>To</label><br />
            <label style="font-weight: bold; font-size: 20px;"
              >Business Name</label
            ><br />
            Address Line 1 <br />
            Address Line 2 <br />
          </td>
          <td style="width: 33%; margin: auto;">
            <span
              style="
                background: #e1e1e1;
                font-size: 30px;
                font-weight: bold;
                padding: 10px;
                color: #343a40;
              "
            >
              DUE/PAID</span
            >
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <tr style="background: #343a40; color: white;">
          <th style="padding: 10px;">
            Description
          </th>
          <th>
            Amount
          </th>
          <th>
            Hours/Unit
          </th>
          <th>
            Vat Rate
          </th>
          <th>
            Total
          </th>
        </tr>
        <tr>
          <td>
            Product 1
          </td>
          <td>
            100
          </td>
          <td>
            1
          </td>
          <td>
            0.00
          </td>
          <td>
            100
          </td>
        </tr>
      </table>
      <table style="width: 100%; position: fixed; bottom: 0;">
        <tr style="width: 100%;">
          <td style="width: 50%;">
            <div>
              Notes:
            </div>
            <div>
              More Information
            </div>
          </td>
          <td
            style="
              width: 50%;
              background-color: whitesmoke;
              text-align: center;
              padding: 10px;
            "
          >
            <label
              style="
                font-size: 30px;
                color: #e1e1e1;
                text-transform: uppercase;
                margin: auto;
              "
              >Signature</label
            >
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>

@stop

@section('css')
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
