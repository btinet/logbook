<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* base.html.twig */
class __TwigTemplate_c68caf6aa16cd496f08c1f15f03ac2a1647efa5a8395ecbbcc16c655bcb13c94 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<html lang=\"de\">
<head>
    <title>Logbuch App</title>
</head>
<body>
    <h1>Hallo</h1>
    ";
        // line 7
        if (($context["id"] ?? null)) {
            // line 8
            echo "        <p>Deine ID lautet: ";
            echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
            echo "</p>
    ";
        }
        // line 10
        echo "    <a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['App\Extension\Twig\TwigExtension']->generateRoute("user/4/edit"), "html", null, true);
        echo "\">Link zum Bearbeiten.</a>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 10,  47 => 8,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<html lang=\"de\">
<head>
    <title>Logbuch App</title>
</head>
<body>
    <h1>Hallo</h1>
    {% if id %}
        <p>Deine ID lautet: {{ id }}</p>
    {% endif %}
    <a href=\"{{ route('user/4/edit') }}\">Link zum Bearbeiten.</a>
</body>
</html>", "base.html.twig", "C:\\MAMP\\htdocs\\logbook\\templates\\base.html.twig");
    }
}
