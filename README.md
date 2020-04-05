# Setup Instruction

### Requirements
1. [Docker](https://docs.docker.com/install/)
2. [Docker Compose](https://docs.docker.com/compose/install/)

### Setup commands
```sh
docker-compose up -d --build
docker exec -it app php artisan migrate
```

# Viewing the App
After docker-compose up is running you can find the application at

[localhost:8777](http://localhost:8777)

# Questions and Answers

> 1. *How long did you spend on the coding test? What would you add to your solution if you had more time? If you didn't spend much time on the coding test then use this as an opportunity to explain what you would add.*

I spent about 3-4 hours on this task. I had to remove all my tests because they needed a lot of mocks for the Google API requests, this is something I would have done had I spent more time on this task.

There's probably a lot I would've done differently or spent more time on but, I didn't think there would be too much value in handing over a production-ready interview task.

If this were for a production or some live-feature I would probably work a bit more on the maintainability of the GoogleMap service and abstracting it once more so that I could more easily mock the api requests in my tests. 


> 2. *Which parts of your solution are you most proud of? And why?*

I was proud of my tests until I realized I would need to mock a lot of data that I wasn't too keen on.

Other than that I think the pushing all my logic into a service was the right decision.

I'm not necessarily proud of any of this code since a bit of work would need to be done to make it a bit more elegant.


> 3. *Which parts did you spend the most time with? What did you find most difficult?*

I choose to use DataTables to help speed up my delivery on the frontend and that absolutely backfired. The documentation could definitely be better for that project.


> 4. *Please describe yourself using JSON*

```json
{
  "judgemental": "false",
  "specialities": ["Coding", "Self-Sustainable Cooking", "Almost Eating Healthily"],
  "occupation": "Chief Technical Officer",
  "name": "Robert Crous",



  "humour": "Dry"
}
```

> 5. *How did you find the test overall? Did you have any issues or have difficulties completing? If you have any suggestions on how we can improve the test, we'd love to hear them.*

I mean, working with APIs is just a frustrating uphill battle.
The Google API is decently documented and it still took me a while to finalize the integration (1-2 hours). The worst part about APIs are they are a pain in the ass to unit test so I had to go with temporary tests which felt like a waste of time.

I would prefer a test that more tests my ability to problem solve practical problems (that are easy to write tests for!).

I'm not suggesting a white board problem but I do find problems such as "build the logic for a working shopping cart" a bit more interesting than "integrate with a 3rd party service".

