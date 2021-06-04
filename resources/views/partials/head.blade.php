<!DOCTYPE html>
<html lang="en">

<head>
  {{-- Meta --}}
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  {{-- CSRF Token --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  {{-- Favicon --}}
  <link rel="icon" href="{{ '/images/favicon.png' }}" type="image/png" sizes="16x16" />

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

  {{-- Bootstrap JS includes --}}
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"
    integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"
    integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/markdown-element/dist/markdown-element.min.js"></script>

  {{-- Stackedit | Remarkable --}}
  <script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/remarkable/1.7.4/remarkable.js" integrity="sha512-QRrpZjZVcoHxp1kQn6MecUMg7rXIE2p8l6kPdlS786pgmsDzYc+x+tlZzui1Spbl6wzLqlCNzGwb4Gt0WM2mew==" crossorigin="anonymous"></script>

  {{-- Page Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/v4-shims.css">
  <script src="https://kit.fontawesome.com/47a2607dc4.js" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>

  {{-- Our CSS --}}
  <link rel="stylesheet" href={{ '/css/fonts.css' }} />
  <link rel="stylesheet" href={{ '/css/colors.css' }} />
  <link rel="stylesheet" href={{ '/css/style.css' }} />
  <link rel="stylesheet" href={{ '/css/footer.css' }} />
  <link rel="stylesheet" href={{ '/css/generic.css' }} />
  <link rel="stylesheet" href={{ '/css/question.css' }} />
  <link rel="stylesheet" href={{ '/css/bootstrap.css' }} />
  <link rel="stylesheet" href={{ '/css/login.css' }} />
  <link rel="stylesheet" href={{ '/css/about.css' }} />
  <link rel="stylesheet" href={{ '/css/moderator.css' }} />
  <link rel="stylesheet" href={{ '/css/aside.css' }} />
  <link rel="stylesheet" href={{ '/css/search.css' }} />
  <link rel="stylesheet" href={{ '/css/question-page.css' }} />
  <link rel="stylesheet" href={{ '/css/ask.css' }} />
  <link rel="stylesheet" href={{ '/css/profile.css' }} />
  
  {{--  Prism  --}}
  <link rel="stylesheet" href={{ '/css/prism.css' }} />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.23.0/prism.min.js" integrity="sha512-YBk7HhgDZvBxmtOfUdvX0z8IH2d10Hp3aEygaMNhtF8fSOvBZ16D/1bXZTJV6ndk/L/DlXxYStP8jrF77v2MIg==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script> 

  {{-- Scripts --}}
  @isset($js)
    @foreach ( $js as $script)
      <script src={{ asset('js/' . $script)}} defer></script>
    @endforeach
    <script src="https://cdnjs.cloudflare.com/ajax/libs/remarkable/1.7.4/remarkable.js" integrity="sha512-QRrpZjZVcoHxp1kQn6MecUMg7rXIE2p8l6kPdlS786pgmsDzYc+x+tlZzui1Spbl6wzLqlCNzGwb4Gt0WM2mew==" crossorigin="anonymous"></script>
  @endisset
</head>
